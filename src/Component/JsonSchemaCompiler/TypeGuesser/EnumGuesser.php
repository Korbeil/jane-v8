<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\EnumType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;

class EnumGuesser implements TypeGuesserInterface
{
    public function guessType(Registry $registry, JsonSchema $schema): ?Type
    {
        if ((0 === \count($schema->type) && \count($schema->enum) > 0)
            || (1 === \count($schema->type) && \in_array(Type::STRING, $schema->type, true))) {
            $types = $detectedTypes = [];
            foreach ($schema->enum as $value) {
                if (\is_string($value) && !\in_array(Type::STRING, $detectedTypes, true)) {
                    $detectedTypes[] = Type::STRING;
                    $types[] = new Type(Type::STRING);
                }
                if (\is_int($value) && !\in_array(Type::INTEGER, $detectedTypes, true)) {
                    $detectedTypes[] = Type::INTEGER;
                    $types[] = new Type(Type::INTEGER);
                }
                if (\is_float($value) && !\in_array(Type::FLOAT, $detectedTypes, true)) {
                    $detectedTypes[] = Type::FLOAT;
                    $types[] = new Type(Type::FLOAT);
                }
            }

            return new EnumType($schema->enum, $types);
        }

        return null;
    }
}
