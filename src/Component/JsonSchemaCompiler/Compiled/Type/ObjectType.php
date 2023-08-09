<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled\Type;

class ObjectType extends Type
{
    public function __construct(
        public readonly string $className,
        public readonly bool $generated = true,
    ) {
        parent::__construct(Type::OBJECT);
    }
}
