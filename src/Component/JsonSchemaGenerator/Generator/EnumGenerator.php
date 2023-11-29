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

        foreach ($enum->values as $value) {
            $enumCaseValue = $this->getEnumCaseValue($value);
            $enumCaseName = $this->getEnumCaseName($value);
            $enumNode->addStmt($factory->enumCase($enumCaseName)->setValue($enumCaseValue));
        }

        $node->addStmt($enumNode);

        $registry->addFile(new File(sprintf('%s/Model/%s.php', $this->configuration->outputDirectory, $enum->name), $node->getNode(), File::TYPE_ENUM));
    }

    /**
     * @param int|float|string $value
     *
     * @return int|string
     */
    private function getEnumCaseValue($value)
    {
        if (\is_float($value)) {
            return (string) $value;
        }

        return $value;
    }

    /**
     * @param int|float|string $value
     */
    private function getEnumCaseName($value): string
    {
        if (\is_int($value) || \is_float($value)) {
            $value = 'VALUE_'.(string) $value;
        }

        return str_replace(['-', '.'], '_', strtoupper($value));
    }
}
