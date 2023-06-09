<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled;

use Jane\Component\JsonSchemaCompiler\Configuration;
use Jane\Component\JsonSchemaCompiler\Exception\NoSourceRegistryException;
use Jane\Component\JsonSchemaMetadata\Metadata\Registry as MetadataRegistry;

class Registry
{
    /** @var array<string, Model> */
    private array $models = [];

    public function __construct(
        public readonly ?string $rootModel = null,
        public readonly ?MetadataRegistry $metadataRegistry = null,
        public readonly ?Configuration $configuration = null,
    ) {
    }

    public function getSource(): MetadataRegistry
    {
        if (null === $this->metadataRegistry) {
            throw new NoSourceRegistryException();
        }

        return $this->metadataRegistry;
    }

    public function addModel(Model $model): void
    {
        $this->models[$model->name] = $model;
    }

    public function getModel(string $name): ?Model
    {
        return $this->models[$name] ?? null;
    }

    /**
     * @return Model[]
     */
    public function getModels(): array
    {
        return $this->models;
    }
}
