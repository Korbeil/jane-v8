<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\ObjectType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaCompiler\Exception\NoSchemaNameException;
use Jane\Component\JsonSchemaCompiler\ModelResolver;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type as MetadataType;

class ObjectGuesser implements TypeGuesserInterface, ChainGuesserAwareInterface
{
    use ChainGuesserAwareTrait {
        setChainGuesser as parentSetChainGuesser;
    }

    private ModelResolver $modelResolver;

    public function guessType(Registry $registry, JsonSchema $schema): ?Type
    {
        if (1 === \count($schema->type) && [MetadataType::OBJECT] === $schema->type) {
            if (null === $schema->name) {
                throw new NoSchemaNameException();
            }

            $objectType = new ObjectType($schema->name);
            $this->modelResolver->resolve($registry, $schema->name, $schema);

            return $objectType;
        }

        return null;
    }

    public function setChainGuesser(ChainGuesser $chainGuesser): void
    {
        $this->parentSetChainGuesser($chainGuesser);
        $this->modelResolver = new ModelResolver(typeGuesser: $chainGuesser);
    }
}

// public function guessType($object, string $name, string $reference, Registry $registry): Type
// {
//    $discriminants = [];
//    $required = $object->getRequired() ?: [];
//
//    foreach ($object->getProperties() as $key => $property) {
//        if (!\in_array($key, $required)) {
//            continue;
//        }
//
//        if ($property instanceof Reference) {
//            $property = $this->resolve($property, $this->getSchemaClass());
//        }
//
//        if (null !== $property->getEnum()) {
//            $isSimple = true;
//            foreach ($property->getEnum() as $value) {
//                if (\is_array($value) || \is_object($value)) {
//                    $isSimple = false;
//                }
//            }
//            if ($isSimple) {
//                $discriminants[$key] = $property->getEnum();
//            }
//        } else {
//            $discriminants[$key] = null;
//        }
//    }
//
//    if ($registry->hasClass($reference) && null !== ($schema = $registry->getSchema($reference))) {
//        return new ObjectType($object, $registry->getClass($reference)->getName(), $schema->getNamespace(), $discriminants);
//    }
//
//    return new Type($object, 'object');
// }
