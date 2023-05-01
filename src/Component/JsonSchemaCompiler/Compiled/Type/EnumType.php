<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled\Type;

class EnumType extends Type
{
    /**
     * @param array<string|integer|float> $values
     */
    public function __construct(
        public array $values,
        string $type,
    ) {
        parent::__construct($type);
    }
}
