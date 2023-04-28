<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled\Type;

class EnumType extends MultipleType
{
    /**
     * @param array<string|integer|float> $values
     * @param Type[]                      $types
     */
    public function __construct(
        public array $values,
        array $types,
    ) {
        parent::__construct($types);
    }
}
