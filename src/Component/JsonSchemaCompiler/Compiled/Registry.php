<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled;

use Jane\Component\JsonSchemaCompiler\Configuration;
use Jane\Component\JsonSchemaCompiler\Exception\NoSourceRegistryException;
use Jane\Component\JsonSchemaMetadata\Metadata\Registry as MetadataRegistry;

class Registry
{
    /** @var array<string, Model> */
    private array $models = [];

    /** @var array<string, string> */
    private array $referenceToModelNameMapping = [];

    /** @var array<string, Enum> */
    private array $enums = [];

    /** @var array<string, string> */
    private array $referenceToEnumNameMapping = [];

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

    public function addModel(Model $model, string $reference): void
    {
        $this->models[$model->name] = $model;
        $this->referenceToModelNameMapping[$reference] = $model->name;
    }

    public function getReferenceModel(string $reference): ?Model
    {
        if (\array_key_exists($reference, $this->referenceToModelNameMapping)) {
            return $this->models[$this->referenceToModelNameMapping[$reference]];
        }

        return null;
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

    public function addEnum(Enum $enum, string $reference): void
    {
        $this->enums[$enum->name] = $enum;
        $this->referenceToEnumNameMapping[$reference] = $enum->name;
    }

    public function getReferenceEnum(string $reference): ?Enum
    {
        if (\array_key_exists($reference, $this->referenceToEnumNameMapping)) {
            return $this->enums[$this->referenceToEnumNameMapping[$reference]];
        }

        return null;
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
