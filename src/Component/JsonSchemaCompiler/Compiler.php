<?php

namespace Jane\Component\JsonSchemaCompiler;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry as CompiledRegistry;
use Jane\Component\JsonSchemaCompiler\Exception\NoSchemaNameException;
use Jane\Component\JsonSchemaCompiler\Naming\Naming;
use Jane\Component\JsonSchemaCompiler\Naming\NamingInterface;
use Jane\Component\JsonSchemaMetadata\Collector;
use Jane\Component\JsonSchemaMetadata\Exception\NoRootModelNameException;
use Jane\Component\JsonSchemaMetadata\Metadata\Registry as MetadataRegistry;

class Compiler implements CompilerInterface
{
    private readonly ModelResolver $modelResolver;

    public function __construct(
        NamingInterface $naming = null,
        private readonly ?Configuration $configuration = null,
    ) {
        $naming = $naming ?? new Naming(clear: true);
        $this->modelResolver = new ModelResolver($naming, configuration: $configuration);
    }

    public function fromPath(string $path, string $rootModel = null): CompiledRegistry
    {
        $collector = new Collector(configuration: $this->configuration);

        return $this->fromMetadata($collector->fromPath($path, $rootModel), $rootModel);
    }

    public function fromMetadata(MetadataRegistry $sourceRegistry, string $rootModel = null): CompiledRegistry
    {
        $registry = new CompiledRegistry(
            rootModel: $rootModel,
            metadataRegistry: $sourceRegistry,
            configuration: $this->configuration,
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
            } elseif (null !== $schema->name) {
                $name = $schema->name;
            } else {
                throw new NoSchemaNameException();
            }

            if (\count($schema->properties) > 0) {
                $this->modelResolver->resolve($registry, $name, $schema);
            }
        }

        return $registry;
    }
}
