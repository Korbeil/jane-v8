<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled\Type;

class MultipleType extends Type
{
    /**
     * @param Type[] $types
     */
    public function __construct(
        public array $types = [],
    ) {
        parent::__construct(Type::MIXED);
    }

    public function addType(Type $type): void
    {
        $this->types[] = $type;
    }
}
