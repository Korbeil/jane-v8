<?php

namespace Jane\Component\JsonSchemaCompiler;

use Jane\Component\JsonSchemaCompiler\Compiled\Enum;
use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Naming\Naming;
use Jane\Component\JsonSchemaCompiler\Naming\NamingInterface;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;

class EnumResolver
{
    private readonly NamingInterface $naming;

    public function __construct(
        NamingInterface $naming = null,
        bool $clearNaming = false,
    ) {
        $this->naming = $naming ?? new Naming(clear: $clearNaming);
    }

    public function resolve(Registry $registry, string $name, string $type, JsonSchema $schema): Enum
    {
        $values = [];
        foreach ($schema->enum as $value) {
            $caseName = $this->naming->getEnumCaseName($value);
            $values[$caseName] = $this->getEnumCaseValue($value);
        }
        $enum = new Enum($this->naming->getEnumName($name), $type, $values);

        $registry->addEnum($enum);

        return $enum;
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
}
