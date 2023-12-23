<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled\Type;

use Jane\Component\JsonSchemaMetadata\Metadata\Type as MetadataType;

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

    public static function isSame(string $metadataType, string $compiledType): bool
    {
        $matchingTypes = [
            MetadataType::NULL->value => self::NULL,
            MetadataType::BOOLEAN->value => self::BOOLEAN,
            MetadataType::INTEGER->value => self::INTEGER,
            MetadataType::NUMBER->value => self::FLOAT,
            MetadataType::STRING->value => self::STRING,
        ];

        if (\array_key_exists($metadataType, $matchingTypes) && $matchingTypes[$metadataType] === $compiledType) {
            return true;
        }

        return false;
    }

    public function isNullable(): bool
    {
        return self::NULL === $this->type;
    }
}
