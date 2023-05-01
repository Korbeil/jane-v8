<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\MultipleType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;

class AnyOfGuesser implements TypeGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserAwareTrait;

    public function guessType(Registry $registry, JsonSchema $schema): ?Type
    {
        if (0 === \count($schema->anyOf)) {
            return null;
        }

        if (1 === \count($schema->anyOf)) {
            return $this->chainGuesser->guessType($registry, $schema->anyOf[0]);
        }

        $type = new MultipleType();
        foreach ($schema->anyOf as $anyOfSchema) {
            $type->addType($this->chainGuesser->guessType($registry, $anyOfSchema));
        }

        return $type;
    }
}
