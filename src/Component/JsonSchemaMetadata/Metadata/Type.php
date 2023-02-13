<?php

namespace Jane\Component\JsonSchemaMetadata\Metadata;

enum Type: string
{
    case ARRAY = 'array';
    case BOOLEAN = 'boolean';
    case INTEGER = 'integer';
    case NUMBER = 'number';
    case NULL = 'null';
    case OBJECT = 'object';
    case STRING = 'string';

    public static function fromName(string $name): self
    {
        foreach (self::cases() as $status) {
            if ($name === $status->value) {
                return $status;
            }
        }

        throw new \ValueError("\"$name\" is not a valid backing value for enum ".self::class);
    }
}
