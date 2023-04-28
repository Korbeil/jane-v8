<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled;

use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;

class Property
{
    public function __construct(
        public string $name,
        public string $phpName,
        public ?string $description = null,
        public Type $type = new Type(Type::OBJECT),
        public bool $hasDefaultValue = false,
        public mixed $defaultValue = null,
        public bool $readOnly = false,
        public bool $deprecated = false,
    ) {
    }
}
