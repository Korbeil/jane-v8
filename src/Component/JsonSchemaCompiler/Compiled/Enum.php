<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled;

class Enum
{
    public readonly string $enumName;

    public function __construct(
        public readonly string $name,
        public readonly string $type,
        /** @var array<string|integer|float> $values */
        public readonly array $values = [],
    ) {
        $this->enumName = $name;
    }
}
