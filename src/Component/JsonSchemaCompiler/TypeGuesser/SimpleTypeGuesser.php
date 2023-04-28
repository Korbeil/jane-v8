<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type as MetadataType;

class SimpleTypeGuesser implements TypeGuesserInterface
{
    private const SUPPORTED_TYPES = [
        MetadataType::BOOLEAN,
        MetadataType::INTEGER,
        MetadataType::NUMBER,
        MetadataType::STRING,
        MetadataType::NULL,
    ];

    public function guessType(Registry $registry, JsonSchema $schema): ?Type
    {
        if (1 === \count($schema->type) && \in_array($schema->type[0], self::SUPPORTED_TYPES, true)) {
            return new Type(match ($schema->type[0]) {
                MetadataType::BOOLEAN => Type::BOOLEAN,
                MetadataType::INTEGER => Type::INTEGER,
                MetadataType::NUMBER => Type::FLOAT,
                MetadataType::STRING => Type::STRING,
                MetadataType::NULL => Type::NULL,
            });
        }

        return null;
    }
}
