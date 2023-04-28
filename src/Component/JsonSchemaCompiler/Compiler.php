<?php

namespace Jane\Component\JsonSchemaCompiler;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry as CompiledRegistry;
use Jane\Component\JsonSchemaMetadata\Collector;
use Jane\Component\JsonSchemaMetadata\Exception\NoRootModelNameException;
use Jane\Component\JsonSchemaMetadata\Metadata\Registry as MetadataRegistry;

class Compiler implements CompilerInterface
{
    private readonly ModelResolver $modelResolver;

    public function __construct()
    {
        $this->modelResolver = new ModelResolver();
    }

    public function fromPath(string $path, string $rootModel = null): CompiledRegistry
    {
        $collector = new Collector();

        return $this->fromMetadata($collector->fromPath($path, $rootModel), $rootModel);
    }

    public function fromMetadata(MetadataRegistry $sourceRegistry, string $rootModel = null): CompiledRegistry
    {
        $registry = new CompiledRegistry(
            rootModel: $rootModel,
            metadataRegistry: $sourceRegistry,
        );

        foreach ($registry->getSource()->all() as $path => $schema) {
            if (str_contains($path, 'property')) {
                continue;
            }

            if (MetadataRegistry::ROOT_ELEMENT === $path) {
                if (null === $rootModel) {
                    throw new NoRootModelNameException();
                }

                $name = $rootModel;
            } else {
                var_dump($path);
                exit;
            }

            $this->modelResolver->resolve($registry, $name, $schema);
        }

        return $registry;
    }
}
