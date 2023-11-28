<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\EnumType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaCompiler\Exception\EnumMultipleTypesFoundException;
use Jane\Component\JsonSchemaCompiler\Exception\EnumNoTypeFoundException;
use Jane\Component\JsonSchemaCompiler\Exception\EnumTypeMismatchException;
use Jane\Component\JsonSchemaCompiler\Exception\NoSchemaNameException;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;

class EnumGuesser implements TypeGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserWithEnumResolverAwareTrait;

    public function guessType(Registry $registry, JsonSchema $schema): ?Type
    {
        if ((0 === \count($schema->type) || 1 === \count($schema->type)) && \count($schema->enum) > 0) {
            if (null === $schema->name) {
                throw new NoSchemaNameException();
            }
            $expectedType = null;
            if (1 === \count($schema->type)) {
                $expectedType = $schema->type[0];
            }

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

            if (null !== $expectedType && !Type::isSame($expectedType->value, $detectedType)) {
                throw new EnumTypeMismatchException();
            }

            $enum = $this->enumResolver->resolve($registry, $schema->name, $schema);

            return new EnumType($enum->enumName, $schema->enum, $detectedType);
        }

        return null;
    }
}
