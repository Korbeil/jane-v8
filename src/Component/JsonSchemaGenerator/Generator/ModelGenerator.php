<?php

namespace Jane\Component\JsonSchemaGenerator\Generator;

use Jane\Component\JsonSchemaCompiler\Compiled\Model;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\ArrayType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\EnumType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\MultipleType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\ObjectType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaGenerator\Configuration;
use Jane\Component\JsonSchemaGenerator\Printer\File;
use Jane\Component\JsonSchemaGenerator\Printer\Registry;
use PhpParser\BuilderFactory;
use PhpParser\Comment;

class ModelGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly Configuration $configuration,
    ) {
    }

    public function generate(Registry $registry, Model $model): void
    {
        $factory = new BuilderFactory();

        $uses = [];
        $parameters = [];
        foreach ($model->getPropertyNames() as $propertyName) {
            $property = $model->getProperty($propertyName);
            if (null === $property) {
                continue;
            }

            /** @phpstan-ignore-next-line */
            $parameterNode = $factory
                ->param($property->phpName)
                ->setType($this->nativeType($property->type, $uses))
                ->makePublic();

            $parameterNode = $parameterNode->getNode();
            if (null !== ($phpDocType = $this->phpDocType($property->type))) {
                $parameterNode->setDocComment(new Comment\Doc($phpDocType));
            }

            $parameters[] = $parameterNode;
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
                            ->addParams($parameters)
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

    private function phpDocType(Type $propertyType, bool $complete = true): ?string
    {
        $phpDocType = null;
        $fakeUseArray = [];
        if ($propertyType instanceof ArrayType) {
            $phpDocType = sprintf('%s[]', $this->nativeType($propertyType->itemsType, $fakeUseArray));
        } elseif ($propertyType instanceof MultipleType) {
            $types = [];
            $shouldWritePhpDoc = false;
            foreach ($propertyType->types as $subType) {
                if ($subType instanceof ArrayType) {
                    $shouldWritePhpDoc = true;
                }

                if (null !== ($phpDocSubType = $this->phpDocType($subType, false))) {
                    $types[] = $phpDocSubType;
                }
            }

            if ($shouldWritePhpDoc) {
                $phpDocType = implode('|', $types);
            }
        } elseif (!$complete) {
            $phpDocType = $propertyType->type;
        }

        if (null === $phpDocType) {
            return null;
        }
        if ($complete) {
            return sprintf('/** @var %s */', $phpDocType);
        }

        return $phpDocType;
    }
}
