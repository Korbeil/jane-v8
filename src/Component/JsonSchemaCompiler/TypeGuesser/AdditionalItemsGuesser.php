<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;

class AdditionalItemsGuesser implements TypeGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserAwareTrait;

    public function guessType(Registry $registry, JsonSchema $schema): ?Type
    {
        if (!$schema->additionalItems instanceof JsonSchema) {
            return null;
        }

        return $this->chainGuesser->guessType($registry, $schema->additionalItems);
    }
}
