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

    public function isNullable(): bool
    {
        foreach ($this->types as $type) {
            if ($type->isNullable()) {
                return true;
            }
        }

        return false;
    }

    public function isA(string $type): bool
    {
        foreach ($this->types as $arrayType) {
            if ($arrayType->isA($type)) {
                return true;
            }
        }

        return false;
    }
}
