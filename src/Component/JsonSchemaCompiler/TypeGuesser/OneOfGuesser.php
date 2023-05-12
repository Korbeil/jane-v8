<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\MultipleType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;

class OneOfGuesser implements TypeGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserAwareTrait;

    public function guessType(Registry $registry, JsonSchema $schema): ?Type
    {
        if (0 === \count($schema->oneOf)) {
            return null;
        }

        if (1 === \count($schema->oneOf)) {
            return $this->chainGuesser->guessType($registry, $schema->oneOf[0]);
        }

        $type = new MultipleType();
        foreach ($schema->oneOf as $oneOfSchema) {
            $type->addType($this->chainGuesser->guessType($registry, $oneOfSchema));
        }

        return $type;
    }
}
