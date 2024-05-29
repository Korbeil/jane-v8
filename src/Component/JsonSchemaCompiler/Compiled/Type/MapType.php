<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled\Type;

class MapType extends ArrayType
{
    public function __construct(
        public Type $itemsType,
    ) {
        parent::__construct($itemsType);
    }

    public function isA(string $type): bool
    {
        return $this->itemsType->isA($type);
    }
}
