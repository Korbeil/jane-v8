<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled\Type;

class DictionaryType extends Type
{
    public function __construct(
        public Type $itemsType,
    ) {
        parent::__construct(Type::OBJECT);
    }
}
