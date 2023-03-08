<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled;

class ModelProperty
{
    public function __construct(
        public string $name,
        /** @var PropertyType[] $type */
        public array $type,
    ) {
    }
}
