<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\ObjectType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaCompiler\Exception\NoSchemaNameException;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type as MetadataType;

class ObjectGuesser implements TypeGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserWithModelResolverAwareTrait;

    public function guessType(Registry $registry, JsonSchema $schema): ?Type
    {
        if (1 === \count($schema->type) && [MetadataType::OBJECT] === $schema->type) {
            if (null === $schema->name) {
                throw new NoSchemaNameException();
            }

            $model = $this->modelResolver->resolve($registry, $schema->name, $schema);

            return new ObjectType($model->modelName);
        }

        return null;
    }
}
