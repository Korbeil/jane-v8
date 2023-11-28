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

    public function resolve(Registry $registry, string $name, JsonSchema $schema): Enum
    {
        $enum = new Enum($this->naming->getEnumName($name), $schema->enum);

        $registry->addEnum($enum);

        return $enum;
    }
}
