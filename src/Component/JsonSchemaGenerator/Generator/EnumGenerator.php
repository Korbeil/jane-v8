<?php

namespace Jane\Component\JsonSchemaGenerator\Generator;

use Jane\Component\JsonSchemaCompiler\Compiled\Enum;
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

        foreach ($enum->values as $value) {
            $enumNode->addStmt($factory->enumCase($value));
        }

        $node->addStmt($enumNode);

        $registry->addFile(new File(sprintf('%s/Model/%s.php', $this->configuration->outputDirectory, $enum->name), $node->getNode(), File::TYPE_ENUM));
    }
}
