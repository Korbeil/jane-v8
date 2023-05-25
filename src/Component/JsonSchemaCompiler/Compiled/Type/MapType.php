<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled\Type;

class MapType extends ArrayType
{
    public function __construct(
        public Type $itemsType,
    ) {
        parent::__construct($itemsType);
    }
}
