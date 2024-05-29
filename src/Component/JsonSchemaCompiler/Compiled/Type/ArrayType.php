<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled\Type;

class ArrayType extends Type
{
    public function __construct(
        public Type $itemsType,
    ) {
        parent::__construct(Type::ARRAY);
    }

    public function isA(string $type): bool
    {
        return $this->itemsType->isA($type);
    }
}
