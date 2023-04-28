<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\MultipleType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;

class MultipleGuesser implements TypeGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserAwareTrait;

    public function guessType(Registry $registry, JsonSchema $schema): ?Type
    {
        if (\count($schema->type) <= 1) {
            return null;
        }

        $typeGuess = new MultipleType();
        $fakeSchema = clone $schema;

        foreach ($schema->type as $type) {
            $fakeSchema->type = [$type];
            $typeGuess->addType($this->chainGuesser->guessType($registry, $fakeSchema));
        }

        return $typeGuess;
    }
}
