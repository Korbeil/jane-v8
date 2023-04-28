<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\ArrayType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\MultipleType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type as MetadataType;

class ArrayGuesser implements TypeGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserAwareTrait;

    public function guessType(Registry $registry, JsonSchema $schema): ?Type
    {
        if (1 === \count($schema->type) && [MetadataType::ARRAY] === $schema->type) {
            if (null === $schema->items) {
                return new ArrayType(new Type(Type::MIXED));
            }

            if ($schema->items instanceof JsonSchema) {
                return new ArrayType($this->chainGuesser->guessType($registry, $schema->items));
            }

            $type = new MultipleType();
            foreach ($schema->items as $itemSchema) {
                $type->addType($this->chainGuesser->guessType($registry, $itemSchema));
            }

            return $type;
        }

        return null;
    }
}
