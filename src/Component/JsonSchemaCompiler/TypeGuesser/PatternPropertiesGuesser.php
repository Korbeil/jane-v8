<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\PatternMultipleType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;

class PatternPropertiesGuesser implements TypeGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserAwareTrait;

    public function guessType(Registry $registry, JsonSchema $schema): ?Type
    {
        if (0 === \count($schema->patternProperties)) {
            return null;
        }

        $type = new PatternMultipleType();

        foreach ($schema->patternProperties as $pattern => $patternSchema) {
            $type->addType($pattern, $this->chainGuesser->guessType($registry, $patternSchema));
        }

        return $type;
    }
}
