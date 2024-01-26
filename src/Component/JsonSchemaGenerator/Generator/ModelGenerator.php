<?php

namespace Jane\Component\JsonSchemaGenerator\Generator;

use Jane\Component\JsonSchemaCompiler\Compiled\Model;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\ArrayType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\EnumType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\MapType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\MultipleType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\ObjectType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaGenerator\Configuration;
use Jane\Component\JsonSchemaGenerator\Printer\File;
use Jane\Component\JsonSchemaGenerator\Printer\Registry;
use PhpParser\BuilderFactory;
use PhpParser\Comment;
use PhpParser\Node\Expr;
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
                ->setType($this->nativeType($property->type, $uses))
                ->makePublic();

            if ($property->readOnly) {
                $parameterNode->makeReadonly();
            }

            $parameterNode = $parameterNode->getNode();
            if (null !== ($phpDocType = $this->phpDocType($property->type, $property->deprecated))) {
                $parameterNode->setDocComment(new Comment\Doc($phpDocType));
            }

            if (null !== $property->defaultValue) {
                $parameterNode->default = $this->getDefaultAsExpr($property->defaultValue);
                $parametersWithDefaultValue[] = $parameterNode;
            } else {
                $parameters[] = $parameterNode;
            }
        }

        $node = $factory
            ->namespace(sprintf('%s\\Model', $this->configuration->baseNamespace));

        foreach ($uses as $use) {
            $node->addStmt($factory->use($use));
        }

        $node
            ->addStmt(
                $factory
                    ->class($model->modelName)
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
     * @param string[] $uses
     */
    private function nativeType(Type $propertyType, array &$uses): string
    {
        if ($propertyType instanceof MultipleType) {
            $unionType = [];
            foreach ($propertyType->types as $subType) {
                $unionType[] = $this->nativeType($subType, $uses);
            }

            return implode('|', $unionType);
        } elseif ($propertyType instanceof ObjectType || $propertyType instanceof EnumType) {
            if (!$propertyType->generated) {
                $uses[] = $propertyType->className;
            }

            return $propertyType->className;
        }

        return $propertyType->type;
    }

    private function phpDocType(Type $propertyType, bool $deprecated, bool $complete = true): ?string
    {
        $phpDocType = null;
        $fakeUseArray = [];
        if ($propertyType instanceof ArrayType) {
            $typingTemplate = '%s[]';
            if ($propertyType instanceof MapType) {
                $typingTemplate = 'array<string, %s>';
            }

            $phpDocType = sprintf($typingTemplate, $this->nativeType($propertyType->itemsType, $fakeUseArray));
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
