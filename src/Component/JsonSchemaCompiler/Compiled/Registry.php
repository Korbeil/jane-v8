<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled;

use Jane\Component\JsonSchemaCompiler\Configuration;
use Jane\Component\JsonSchemaCompiler\Exception\NoSourceRegistryException;
use Jane\Component\JsonSchemaMetadata\Metadata\Registry as MetadataRegistry;

class Registry
{
    /** @var array<string, Model> */
    private array $models = [];

    /** @var array<string, Enum> */
    private array $enums = [];

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

    public function addEnum(Enum $enum): void
    {
        $this->enums[$enum->name] = $enum;
    }

    public function getEnum(string $name): ?Enum
    {
        return $this->enums[$name] ?? null;
    }

    /**
     * @return Enum[]
     */
    public function getEnums(): array
    {
        return $this->enums;
    }
}
