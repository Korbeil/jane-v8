<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\MultipleType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type as MetadataType;

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
            if (MetadataType::NULL === $type) {
                $typeGuess->addType(new Type(Type::NULL));
            } else {
                $loopFakeSchema = clone $fakeSchema;
                $loopFakeSchema->type = [$type];
                $typeGuess->addType($this->chainGuesser->guessType($registry, $loopFakeSchema));
            }
        }

        return $typeGuess;
    }
}
