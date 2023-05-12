<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;

class AllOfGuesser implements TypeGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserAwareTrait;

    public function guessType(Registry $registry, JsonSchema $schema): ?Type
    {
        if (0 === \count($schema->allOf)) {
            return null;
        }

        if (1 === \count($schema->allOf)) {
            return $this->chainGuesser->guessType($registry, $schema->allOf[0]);
        }

        $allOfRoot = null;
        foreach ($schema->allOf as $allOfSchema) {
            if (null !== $allOfRoot) {
                $allOfSchema->merge($allOfRoot);
            }

            $allOfRoot = $allOfSchema;
        }

        return $this->chainGuesser->guessType($registry, $allOfRoot);
    }
}
