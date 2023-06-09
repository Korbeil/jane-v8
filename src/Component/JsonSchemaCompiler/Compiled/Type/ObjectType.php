<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled\Type;

class ObjectType extends Type
{
    public function __construct(
        public readonly string $className,
    ) {
        parent::__construct(Type::OBJECT);
    }
}
