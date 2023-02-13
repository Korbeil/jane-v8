<?php

namespace Jane\Component\JsonSchemaMetadata\NodeTraverser;

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

    /**
     * @param array $data
     */
    public function traverse(mixed $data, string $reference): bool
    {
        $properties = [];
        foreach ($data['properties'] ?? [] as $propertyName => $property) {
            $this->chainNodeTraverser->traverse($property, $propertyReference = sprintf('%s/property/%s', $reference, $propertyName));

            $propertySchema = $this->registry->get($propertyReference);
            if (null === $propertySchema) {
                continue;
            }
            $properties[$propertyName] = $propertySchema;
        }

        $schema = new JsonSchema(
            title: $data['title'] ?? null,
            description: $data['description'] ?? null,
            defaultValue: $data['default'] ?? null,
            hasDefaultValue: \array_key_exists('default', $data),
            deprecated: $data['deprecated'] ?? false,
            readOnly: $data['readOnly'] ?? false,
            writeOnly: $data['writeOnly'] ?? false,

            additionalProperties: $data['additionalProperties'] ?? true,
            properties: $properties,

            type: \array_key_exists('type', $data) ? Type::fromName($data['type']) : Type::OBJECT,
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
            contentSchema: $data['contentSchema'] ?? null,
        );

        $this->registry->addSchema($reference, $schema);

        return true;
    }
}
