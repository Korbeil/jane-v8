<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled;

enum PropertyType: string
{
    case NULL = 'null';
    case BOOL = 'bool';
    case INT = 'int';
    case FLOAT = 'float';
    case STRING = 'string';
    case ARRAY = 'array';
    case OBJECT = 'object';
}
