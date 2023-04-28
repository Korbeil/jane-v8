<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled\Type;

class Type
{
    public const NULL = 'null';
    public const BOOLEAN = 'bool';
    public const INTEGER = 'int';
    public const FLOAT = 'float';
    public const STRING = 'string';
    public const MIXED = 'mixed';
    public const ARRAY = 'array';
    public const OBJECT = 'object';

    public function __construct(
        public string $type,
    ) {
    }
}
