<?php

namespace Jane\Component\JsonSchemaMetadata\NodeTraverser;

use Jane\Component\JsonSchemaMetadata\Exception\NoModelNameException;
use Jane\Component\JsonSchemaMetadata\Exception\NoRootModelNameException;
use Jane\Component\JsonSchemaMetadata\Metadata\Format;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Registry;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;

class JsonSchemaTraverser implements NodeTraverserInterface
{
    public function __construct(
        private readonly Registry $registry,
        private readonly NodeTraverserInterface $chainNodeTraverser,
    ) {
    }

    public function traverse(array $data, string $reference, array $context = []): bool
    {
        $name = $context[NodeTraverserInterface::CONTEXT_SCHEMA_NAME] ?? null;
        if (null === $name && Registry::ROOT_ELEMENT === $reference) {
            throw new NoRootModelNameException();
        }

        $schema = new JsonSchema(
            name: $name,
            title: $data['title'] ?? null,
            description: $data['description'] ?? null,
            defaultValue: $data['default'] ?? null,
            hasDefaultValue: \array_key_exists('default', $data),
            deprecated: $data['deprecated'] ?? false,
            readOnly: $data['readOnly'] ?? false,
            writeOnly: $data['writeOnly'] ?? false,

            additionalProperties: $this->getAdditionalProperties($data, $reference, $context),
            properties: $this->getProperties($data, $reference, $context),
            patternProperties: $this->getPatternProperties($data, $reference, $context),

            oneOf: $this->getOneOf($data, $reference, $context),
            allOf: $this->getAllOf($data, $reference, $context),
            anyOf: $this->getAnyOf($data, $reference, $context),

            type: $this->getTypes($data),
            enum: $data['enum'] ?? [],
            constValue: $data['const'] ?? null,
            hasConstValue: \array_key_exists('const', $data),

            multipleOf: $data['multipleOf'] ?? null,
            minimum: $data['minimum'] ?? null,
            exclusiveMinimum: $data['exclusiveMinimum'] ?? null,
            maximum: $data['maximum'] ?? null,
            exclusiveMaximum: $data['exclusiveMaximum'] ?? null,

            minLength: $data['minLength'] ?? null,
            maxLength: $data['maxLength'] ?? null,
            pattern: $data['pattern'] ?? null,

            items: $this->getItems($data, $reference, $context),
            prefixItems: $this->getPrefixItems($data, $reference, $context),
            minItems: $data['minItems'] ?? null,
            maxItems: $data['maxItems'] ?? null,
            uniqueItems: $data['uniqueItems'] ?? false,
            contains: $data['contains'] ?? null,
            hasContains: \array_key_exists('contains', $data),
            minContains: $data['minContains'] ?? null,
            maxContains: $data['maxContains'] ?? null,

            minProperties: $data['minProperties'] ?? null,
            maxProperties: $data['maxProperties'] ?? null,
            required: $data['required'] ?? [],
            dependentRequired: $data['dependentRequired'] ?? [],

            format: \array_key_exists('format', $data) ? Format::fromName($data['format']) : null,

            contentEncoding: $data['contentEncoding'] ?? null,
            contentMediaType: $data['contentMediaType'] ?? null,
            contentSchema: $this->getContentSchema($data, $reference, $context),
        );

        $this->registry->addSchema($reference, $schema);

        return true;
    }

    /**
     * @param JsonSchemaDefinition $data
     * @param JsonSchemaContext    $context
     */
    private function getAdditionalProperties(array $data, string $reference, array $context): bool|JsonSchema
    {
        $additionalProperties = true;
        if (\array_key_exists('additionalProperties', $data) && false === $data['additionalProperties']) {
            $additionalProperties = false;
        }
        if (\array_key_exists('additionalProperties', $data) && \is_array($data['additionalProperties'])) {
            $this->chainNodeTraverser->traverse($data['additionalProperties'], $additionalPropertiesReference = sprintf('%s/additionalProperties', $reference), $context);
            $additionalPropertiesSchema = $this->registry->get($additionalPropertiesReference);
            if (null !== $additionalPropertiesSchema) {
                /** @var bool|JsonSchema $additionalProperties */
                $additionalProperties = $additionalPropertiesSchema;
            }
        }

        return $additionalProperties;
    }

    /**
     * @param JsonSchemaDefinition $data
     * @param JsonSchemaContext    $context
     *
     * @return array<string, JsonSchema>
     */
    private function getProperties(array $data, string $reference, array $context): array
    {
        $schemaName = $context[NodeTraverserInterface::CONTEXT_SCHEMA_NAME] ?? null;
        if (null === $schemaName) {
            throw new NoModelNameException();
        }

        $properties = [];
        /**
         * @var string               $propertyName
         * @var JsonSchemaDefinition $property
         */
        foreach ($data['properties'] ?? [] as $propertyName => $property) {
            $this->chainNodeTraverser->traverse($property, $propertyReference = sprintf('%s/property/%s', $reference, $propertyName), array_merge($context, [NodeTraverserInterface::CONTEXT_SCHEMA_NAME => $schemaName.ucfirst($propertyName)]));

            $propertySchema = $this->registry->get($propertyReference);
            if (null === $propertySchema) {
                continue;
            }
            $properties[$propertyName] = $propertySchema;
        }

        return $properties;
    }

    /**
     * @param JsonSchemaDefinition $data
     * @param JsonSchemaContext    $context
     *
     * @return array<string, JsonSchema>
     */
    private function getPatternProperties(array $data, string $reference, array $context): array
    {
        $patternProperties = [];
        /**
         * @var string               $propertyPattern
         * @var JsonSchemaDefinition $property
         */
        foreach ($data['patternProperties'] ?? [] as $propertyPattern => $property) {
            $this->chainNodeTraverser->traverse($property, $propertyPatternReference = sprintf('%s/propertyPattern/%s', $reference, $propertyPattern), $context);

            $propertyPatternSchema = $this->registry->get($propertyPatternReference);
            if (null === $propertyPatternSchema) {
                continue;
            }
            $patternProperties[$propertyPattern] = $propertyPatternSchema;
        }

        return $patternProperties;
    }

    /**
     * @param JsonSchemaDefinition $data
     * @param JsonSchemaContext    $context
     *
     * @return array<JsonSchema>
     */
    private function getOneOf(array $data, string $reference, array $context): array
    {
        $oneOf = [];
        if (\array_key_exists('oneOf', $data) && \count($data['oneOf']) > 0) {
            /** @var JsonSchemaDefinition $oneOfData */
            foreach ($data['oneOf'] as $k => $oneOfData) {
                $this->chainNodeTraverser->traverse($oneOfData, $oneOfReference = sprintf('%s/oneOf/%s', $reference, $k), $context);
                if (null !== ($oneOfSchema = $this->registry->get($oneOfReference))) {
                    $oneOf[] = $oneOfSchema;
                }
            }
        }

        return $oneOf;
    }

    /**
     * @param JsonSchemaDefinition $data
     * @param JsonSchemaContext    $context
     *
     * @return array<JsonSchema>
     */
    private function getAllOf(array $data, string $reference, array $context): array
    {
        $allOf = [];
        if (\array_key_exists('allOf', $data) && \count($data['allOf']) > 0) {
            /** @var JsonSchemaDefinition $allOfData */
            foreach ($data['allOf'] as $k => $allOfData) {
                $this->chainNodeTraverser->traverse($allOfData, $allOfReference = sprintf('%s/allOf/%s', $reference, $k), $context);
                if (null !== ($allOfSchema = $this->registry->get($allOfReference))) {
                    $allOf[] = $allOfSchema;
                }
            }
        }

        return $allOf;
    }

    /**
     * @param JsonSchemaDefinition $data
     * @param JsonSchemaContext    $context
     *
     * @return array<JsonSchema>
     */
    private function getAnyOf(array $data, string $reference, array $context): array
    {
        $anyOf = [];
        if (\array_key_exists('anyOf', $data) && \count($data['anyOf']) > 0) {
            /** @var JsonSchemaDefinition $anyOfData */
            foreach ($data['anyOf'] as $k => $anyOfData) {
                $this->chainNodeTraverser->traverse($anyOfData, $anyOfReference = sprintf('%s/anyOf/%s', $reference, $k), $context);
                if (null !== ($anyOfSchema = $this->registry->get($anyOfReference))) {
                    $anyOf[] = $anyOfSchema;
                }
            }
        }

        return $anyOf;
    }

    /**
     * @param JsonSchemaDefinition $data
     *
     * @return array<Type>
     */
    private function getTypes(array $data): array
    {
        $types = [];
        if (\array_key_exists('type', $data)) {
            if (!\is_array($data['type'])) {
                $data['type'] = [$data['type']];
            }

            foreach ($data['type'] as $type) {
                $types[] = Type::fromName($type);
            }
        }

        return $types;
    }

    /**
     * @param JsonSchemaDefinition $data
     * @param JsonSchemaContext    $context
     *
     * @return JsonSchema|array<JsonSchema>|null
     */
    private function getItems(array $data, string $reference, array $context): null|JsonSchema|array
    {
        $items = null;
        if (\array_key_exists('items', $data)) {
            /** @var JsonSchemaDefinition $itemsSchema */
            $itemsSchema = $data['items'];
            $this->chainNodeTraverser->traverse($itemsSchema, $itemsReference = sprintf('%s/items', $reference), $context);
            $items = $this->registry->get($itemsReference);

            if ($items instanceof JsonSchema && $items->isEmpty() && \is_array($data['items']) && \count($data['items']) > 0) {
                $items = [];
                foreach ($data['items'] as $itemKey => $itemSchema) {
                    $this->chainNodeTraverser->traverse($itemsSchema, $itemReference = sprintf('%s/items/%s', $reference, $itemKey), $context);
                    if (($resolvedItem = $this->registry->get($itemReference)) instanceof JsonSchema) {
                        $items[] = $resolvedItem;
                    }
                }
            }
        }

        return $items;
    }

    /**
     * @param JsonSchemaDefinition $data
     * @param JsonSchemaContext    $context
     *
     * @return array<JsonSchema>
     */
    private function getPrefixItems(array $data, string $reference, array $context): array
    {
        $prefixItems = [];
        if (\array_key_exists('prefixItems', $data) && \count($data['prefixItems']) > 0) {
            /** @var JsonSchemaDefinition $prefixItem */
            foreach ($data['prefixItems'] as $k => $prefixItem) {
                $this->chainNodeTraverser->traverse($prefixItem, $prefixItemReference = sprintf('%s/prefixItems/%d', $reference, $k), $context);
                if (null !== ($prefixItemSchema = $this->registry->get($prefixItemReference))) {
                    $prefixItems[] = $prefixItemSchema;
                }
            }
        }

        return $prefixItems;
    }

    /**
     * @param JsonSchemaDefinition $data
     * @param JsonSchemaContext    $context
     */
    private function getContentSchema(array $data, string $reference, array $context): null|JsonSchema
    {
        $contentSchema = null;
        if (\array_key_exists('contentSchema', $data)) {
            /** @var JsonSchemaDefinition $contentSchemaDefinition */
            $contentSchemaDefinition = $data['contentSchema'];
            $this->chainNodeTraverser->traverse($contentSchemaDefinition, $contentSchemaReference = sprintf('%s/content-schema', $reference), $context);
            $contentSchema = $this->registry->get($contentSchemaReference);
        }

        return $contentSchema;
    }
}
