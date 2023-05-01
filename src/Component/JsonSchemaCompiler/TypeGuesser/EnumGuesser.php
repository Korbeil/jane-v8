<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\EnumType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaCompiler\Exception\EnumMultipleTypesFoundException;
use Jane\Component\JsonSchemaCompiler\Exception\EnumNoTypeFoundException;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;

class EnumGuesser implements TypeGuesserInterface
{
    public function guessType(Registry $registry, JsonSchema $schema): ?Type
    {
        if ((0 === \count($schema->type) && \count($schema->enum) > 0)
            || (1 === \count($schema->type) && \in_array(Type::STRING, $schema->type, true))) {
            $detectedType = null;

            foreach ($schema->enum as $value) {
                /** @var 'float'|'int'|'string'|null $valueType */
                $valueType = null;
                if (\is_string($value)) {
                    $valueType = Type::STRING;
                }
                if (\is_int($value)) {
                    $valueType = Type::INTEGER;
                }
                if (\is_float($value)) {
                    $valueType = Type::FLOAT;
                }

                if (null === $valueType) {
                    throw new EnumNoTypeFoundException();
                } elseif (null === $detectedType || $valueType === $detectedType) {
                    $detectedType = $valueType;
                } else {
                    throw new EnumMultipleTypesFoundException();
                }
            }

            if (null === $detectedType) {
                throw new EnumNoTypeFoundException();
            }

            return new EnumType($schema->enum, $detectedType);
        }

        return null;
    }
}
