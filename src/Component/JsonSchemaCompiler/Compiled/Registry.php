<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled;

use Jane\Component\JsonSchemaCompiler\Configuration;
use Jane\Component\JsonSchemaCompiler\Exception\NoSourceRegistryException;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Registry as MetadataRegistry;

class Registry
{
    /** @var array<string, string> */
    private array $modelHashes = [];

    /** @var array<string, Model> */
    private array $models = [];

    /** @var array<string, string> */
    private array $enumHashes = [];
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

    public function addModel(Model $model, JsonSchema $schema): void
    {
        $this->models[$model->name] = $model;
        $this->modelHashes[$model->name] = $schema->makeHash();
    }

    public function getModel(string $name): ?Model
    {
        return $this->models[$name] ?? null;
    }

    public function getModelHash(string $name): ?string
    {
        return $this->modelHashes[$name] ?? null;
    }

    /**
     * @return Model[]
     */
    public function getModels(): array
    {
        return $this->models;
    }

    public function addEnum(Enum $enum, string $name, JsonSchema $schema): void
    {
        $this->enums[$name] = $enum;
        $this->enumHashes[$name] = $schema->makeHash();
    }

    public function getEnum(string $name): ?Enum
    {
        return $this->enums[$name] ?? null;
    }

    public function getEnumHash(string $name): ?string
    {
        return $this->enumHashes[$name] ?? null;
    }

    /**
     * @return Enum[]
     */
    public function getEnums(): array
    {
        return $this->enums;
    }
}
