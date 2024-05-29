<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled\Type;

class PatternMultipleType extends Type
{
    /**
     * @param array<string, Type> $types
     */
    public function __construct(
        public array $types = [],
    ) {
        parent::__construct(Type::MIXED);
    }

    public function addType(string $pattern, Type $type): void
    {
        $this->types[$pattern] = $type;
    }

    public function isA(string $type): bool
    {
        foreach ($this->types as $currentType) {
            if ($currentType->isA($type)) {
                return true;
            }
        }

        return false;
    }
}
