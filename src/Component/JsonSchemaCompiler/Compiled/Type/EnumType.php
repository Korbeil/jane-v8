<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled\Type;

class EnumType extends Type
{
    /**
     * @param array<string|integer|float> $values
     */
    public function __construct(
        public readonly string $className,
        public array $values,
        string $type,
        public readonly bool $generated = true,
    ) {
        parent::__construct($type);
    }
}
