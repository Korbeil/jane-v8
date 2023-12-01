<?php

namespace Jane\Component\JsonSchemaGenerator\Generator;

use Jane\Component\JsonSchemaCompiler\Compiled\Enum;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaGenerator\Configuration;
use Jane\Component\JsonSchemaGenerator\Printer\File;
use Jane\Component\JsonSchemaGenerator\Printer\Registry;
use PhpParser\BuilderFactory;

class EnumGenerator
{
    public function __construct(
        private readonly Configuration $configuration,
    ) {
    }

    public function generate(Registry $registry, Enum $enum): void
    {
        $factory = new BuilderFactory();

        $node = $factory
            ->namespace(sprintf('%s\\Model', $this->configuration->baseNamespace));

        $enumNode = $factory->enum($enum->enumName);
        $enumType = Type::INTEGER === $enum->type ? 'int' : 'string';
        $enumNode->setScalarType($enumType);

        foreach ($enum->values as $name => $value) {
            $enumNode->addStmt($factory->enumCase($name)->setValue($value));
        }

        $node->addStmt($enumNode);

        $registry->addFile(new File(sprintf('%s/Model/%s.php', $this->configuration->outputDirectory, $enum->name), $node->getNode(), File::TYPE_ENUM));
    }
}
