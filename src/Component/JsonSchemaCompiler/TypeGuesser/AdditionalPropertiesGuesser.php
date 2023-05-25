<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\MapType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;

class AdditionalPropertiesGuesser implements TypeGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserAwareTrait;

    public function guessType(Registry $registry, JsonSchema $schema): ?Type
    {
        if (false === $schema->additionalProperties) {
            return null;
        }

        if (true === $schema->additionalProperties) {
            return new MapType(new Type(Type::MIXED));
        }

        return new MapType($this->chainGuesser->guessType($registry, $schema->additionalProperties));
    }
}
