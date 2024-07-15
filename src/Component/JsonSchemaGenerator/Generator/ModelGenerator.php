<?php

namespace Jane\Component\JsonSchemaGenerator\Generator;

use AutoMapper\Attribute\MapFrom;
use AutoMapper\Attribute\MapTo;
use Jane\Component\JsonSchemaCompiler\Compiled\Model;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\ArrayType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\EnumType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\MapType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\MultipleType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\ObjectType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\PatternMultipleType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaGenerator\Configuration;
use Jane\Component\JsonSchemaGenerator\Printer\File;
use Jane\Component\JsonSchemaGenerator\Printer\Registry;
use PhpParser\BuilderFactory;
use PhpParser\Comment;
use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt;
use PhpParser\Parser;
use PhpParser\ParserFactory;

class ModelGenerator implements GeneratorInterface
{
    private readonly Parser $parser;

    public function __construct(
        private readonly Configuration $configuration,
    ) {
        $this->parser = (new ParserFactory())->createForHostVersion();
    }

    public function generate(Registry $registry, Model $model): void
    {
        $factory = new BuilderFactory();

        $uses = [];
        $parameters = [];
        $parametersWithDefaultValue = [];
        foreach ($model->getPropertyNames() as $propertyName) {
            $property = $model->getProperty($propertyName);
            if (null === $property) {
                continue;
            }

            $parameterNode = $factory
                ->param($property->phpName)
                ->setType(implode('|', array_keys($this->nativeTypes($property->type, $uses))))
                ->makePublic();

            if ($property->readOnly) {
                $parameterNode->makeReadonly();
            }

            if ($property->type->isA('date')) {
                $this->addAutoMapperAttributesToUses($uses);

                $parameterNode->addAttribute(new Attribute(new Name('MapTo'), [new Arg(new Scalar\String_($this->configuration->dateFormat), name: new Identifier('dateTimeFormat'))]));
                $parameterNode->addAttribute(new Attribute(new Name('MapFrom'), [new Arg(new Scalar\String_($this->configuration->dateFormat), name: new Identifier('dateTimeFormat'))]));
            } elseif ($property->type->isA('date-time')) {
                $this->addAutoMapperAttributesToUses($uses);

                $parameterNode->addAttribute(new Attribute(new Name('MapTo'), [new Arg(new Scalar\String_($this->configuration->dateTimeFormat), name: new Identifier('dateTimeFormat'))]));
                $parameterNode->addAttribute(new Attribute(new Name('MapFrom'), [new Arg(new Scalar\String_($this->configuration->dateTimeFormat), name: new Identifier('dateTimeFormat'))]));
            }

            $parameterNode = $parameterNode->getNode();
            if (null !== ($phpDocType = $this->phpDocType($property->type, $property->deprecated))) {
                $parameterNode->setDocComment(new Comment\Doc($phpDocType));
            }

            if ($property->hasDefaultValue) {
                $parameterNode->default = $this->getDefaultAsExpr($property->defaultValue);
                $parametersWithDefaultValue[] = $parameterNode;
            } else {
                $parameters[] = $parameterNode;
            }
        }

        $classFactory = $factory->class($model->modelName);

        if (null !== $model->additionalProperties) {
            $registry->needsAdditionalPropertiesRuntime = true;
            $uses[] = ($additionalPropertiesInterface = sprintf('\\%s\\Runtime\\AdditionalPropertiesInterface', $this->configuration->baseNamespace));
            $classFactory->implement($additionalPropertiesInterface);
        }
        if (null !== $model->patternProperties) {
            $registry->needsPatternPropertiesRuntime = true;
            $uses[] = ($patternPropertiesInterface = sprintf('\\%s\\Runtime\\PatternPropertiesInterface', $this->configuration->baseNamespace));
            $classFactory->implement($patternPropertiesInterface);
        }
        if (null !== $model->additionalProperties || null !== $model->patternProperties) {
            $uses[] = sprintf('%s\\Runtime\\AdditionalAndPatternProperties', $this->configuration->baseNamespace);
        }

        $node = $factory
            ->namespace(sprintf('%s\\Model', $this->configuration->baseNamespace));

        foreach ($uses as $use) {
            $node->addStmt($factory->use($use));
        }

        $node
            ->addStmt(
                $classFactory
                    ->addStmt(
                        $factory
                            ->method('__construct')
                            ->makePublic()
                            ->addParams(array_merge($parameters, $parametersWithDefaultValue))
                    )
            );

        $registry->addFile(new File(sprintf('%s/Model/%s.php', $this->configuration->outputDirectory, $model->name), $node->getNode(), File::TYPE_MODEL));
    }

    /**
     * @param list<string> $uses
     */
    private function addAutoMapperAttributesToUses(array &$uses): void
    {
        if (!\in_array(MapTo::class, $uses, true)) {
            $uses[] = MapTo::class;
        }

        if (!\in_array(MapFrom::class, $uses, true)) {
            $uses[] = MapFrom::class;
        }
    }

    /**
     * @param string[] $uses
     *
     * @return array<string, bool>
     */
    private function nativeTypes(Type $propertyType, array &$uses): array
    {
        if ($propertyType instanceof MultipleType || $propertyType instanceof PatternMultipleType) {
            $unionType = [];
            foreach ($propertyType->types as $subType) {
                foreach ($this->nativeTypes($subType, $uses) as $type => $_) {
                    if (!\array_key_exists($type, $unionType)) {
                        $unionType[$type] = true;
                    }
                }
            }

            return $unionType;
        } elseif ($propertyType instanceof ObjectType || $propertyType instanceof EnumType) {
            if (!$propertyType->generated && !\in_array($propertyType->className, $uses, true)) {
                $uses[] = $propertyType->className;
            }

            return [$propertyType->className => true];
        }

        return [$propertyType->type => true];
    }

    /**
     * @fixme dedupe types
     */
    private function phpDocType(Type $propertyType, bool $deprecated, bool $complete = true): ?string
    {
        $phpDocType = null;
        $fakeUseArray = [];
        if ($propertyType instanceof ArrayType) {
            $typingTemplate = '%s[]';
            if ($propertyType instanceof MapType) {
                $typingTemplate = 'array<string, %s>';
            }

            $itemTypes = $propertyType->itemsType;
            if ($propertyType->itemsType instanceof MultipleType) {
                $itemTypesArray = [];
                foreach ($propertyType->itemsType->types as $type) {
                    if (Type::NULL === $type->type) {
                        continue;
                    }

                    $itemTypesArray[] = new Type($type->type);
                }

                if (1 === \count($itemTypesArray)) {
                    $itemTypes = $itemTypesArray[0];
                } else {
                    $itemTypes = new MultipleType($itemTypesArray);
                }
            }

            $phpDocType = sprintf($typingTemplate, implode('|', array_keys($this->nativeTypes($itemTypes, $fakeUseArray))));
        } elseif ($propertyType instanceof MultipleType) {
            $types = [];
            $shouldWritePhpDoc = false;
            foreach ($propertyType->types as $subType) {
                if ($subType instanceof ArrayType) {
                    $shouldWritePhpDoc = true;
                }

                if (null !== ($phpDocSubType = $this->phpDocType($subType, $deprecated, false))) {
                    $types[] = $phpDocSubType;
                }
            }

            if ($shouldWritePhpDoc) {
                $phpDocType = implode('|', $types);
            }
        } elseif (!$complete) {
            $phpDocType = $propertyType->type;
        }

        if ($complete) {
            if (null === $phpDocType) {
                if ($deprecated) {
                    return '/** @deprecated */';
                }

                return null;
            }

            if ($deprecated) {
                return sprintf(<<<DOC
    /**
     * @var %s
     * @deprecated
     */
DOC, $phpDocType);
            }

            return sprintf('/** @var %s */', $phpDocType);
        }

        return $phpDocType;
    }

    /**
     * Generate a default value as an Expr.
     */
    private function getDefaultAsExpr(mixed $defaultValue): Expr
    {
        /** @var Stmt\Expression[]|Expr[] $stmts */
        $stmts = $this->parser->parse('<?php '.var_export($defaultValue, true).';');
        $expr = $stmts[0];

        if ($expr instanceof Stmt\Expression) {
            return $expr->expr;
        }

        return $expr;
    }
}
