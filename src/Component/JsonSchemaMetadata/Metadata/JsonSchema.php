<?php

namespace Jane\Component\JsonSchemaMetadata\Metadata;

class JsonSchema
{
    /** @var array<Type> */
    public array $type = [Type::OBJECT];

    /**
     * @param Type|array<Type> $type
     */
    public function __construct(
        public ?string $name = null,

        // Vocabulary for Basic Meta-Data Annotations
        public ?string $title = null,
        public ?string $description = null,
        public mixed $defaultValue = null,
        public bool $hasDefaultValue = false,
        public bool $deprecated = false,
        public bool $readOnly = false,
        public bool $writeOnly = false,

        // Core keywords
        public bool|self $additionalProperties = true,
        /** @var array<string, self> $properties */
        public array $properties = [],
        /** @var array<string, self> $patternProperties */
        public array $patternProperties = [],

        // xOf
        /** @var array<self> $oneOf */
        public array $oneOf = [],
        /** @var array<self> $allOf */
        public array $allOf = [],
        /** @var array<self> $anyOf */
        public array $anyOf = [],

        // Keywords for Any Instance Type
        Type|array $type = [],
        /** @var array<string|integer|float> $enum */
        public array $enum = [],
        public mixed $constValue = null,
        public bool $hasConstValue = false,

        // Keywords for Numeric Instances (number and integer)
        public ?int $multipleOf = null,
        public ?int $minimum = null,
        public ?int $exclusiveMinimum = null,
        public ?int $maximum = null,
        public ?int $exclusiveMaximum = null,

        // Keywords for Strings
        public ?int $minLength = null,
        public ?int $maxLength = null,
        public ?string $pattern = null,

        // Keywords for Arrays
        /** @var array<JsonSchema>|JsonSchema|null */
        public null|self|array $items = null,
        public ?self $additionalItems = null,
        /** @var array<self> $prefixItems */
        public array $prefixItems = [],
        public ?int $minItems = null,
        public ?int $maxItems = null,
        public bool $uniqueItems = false,
        public mixed $contains = null,
        public bool $hasContains = false,
        public ?int $minContains = null,
        public ?int $maxContains = null,

        // Keywords for Objects
        public ?int $minProperties = null,
        public ?int $maxProperties = null,
        /** @var string[] $required */
        public array $required = [],
        /** @var array<string, string[]> $dependentRequired */
        public array $dependentRequired = [],

        // Vocabularies for Semantic Content With "format"
        public ?Format $format = null,

        // Vocabulary for the Contents of String-Encoded Data
        public ?string $contentEncoding = null,
        public ?string $contentMediaType = null,
        public ?self $contentSchema = null,
    ) {
        if ($type instanceof Type) {
            $type = [$type];
        }
        $this->type = $type;
    }

    public function merge(self $schema): void
    {
        $updateIfNotNull = fn (mixed $source, mixed & $target) => (null !== $source) ? $target = $source : null;

        $updateIfNotNull($schema->title, $this->title);
        $updateIfNotNull($schema->description, $this->description);
        if ($schema->hasDefaultValue) {
            $this->hasDefaultValue = true;
            $this->defaultValue = $schema->defaultValue;
        }

        $updateIfNotNull($schema->deprecated, $this->deprecated);
        $updateIfNotNull($schema->readOnly, $this->readOnly);
        $updateIfNotNull($schema->writeOnly, $this->writeOnly);

        if (false === $schema->additionalProperties) {
            $this->additionalProperties = $schema->additionalProperties;
        }
        if (\count($schema->properties) > 0) {
            $this->properties = $schema->properties;
        }
        if (\count($schema->patternProperties) > 0) {
            $this->patternProperties = $schema->patternProperties;
        }

        if (\count($schema->oneOf) > 0) {
            $this->oneOf = $schema->oneOf;
        }
        if (\count($schema->allOf) > 0) {
            $this->allOf = $schema->allOf;
        }
        if (\count($schema->anyOf) > 0) {
            $this->anyOf = $schema->anyOf;
        }

        $this->type = $schema->type;
        if (\count($schema->enum) > 0) {
            $this->enum = $schema->enum;
        }
        if ($schema->hasConstValue) {
            $this->hasConstValue = true;
            $this->constValue = $schema->constValue;
        }

        $updateIfNotNull($schema->multipleOf, $this->multipleOf);
        $updateIfNotNull($schema->minimum, $this->minimum);
        $updateIfNotNull($schema->exclusiveMinimum, $this->exclusiveMinimum);
        $updateIfNotNull($schema->maximum, $this->maximum);
        $updateIfNotNull($schema->exclusiveMaximum, $this->exclusiveMaximum);

        $updateIfNotNull($schema->minLength, $this->minLength);
        $updateIfNotNull($schema->maxLength, $this->maxLength);
        $updateIfNotNull($schema->pattern, $this->pattern);

        $updateIfNotNull($schema->items, $this->items);
        if (\count($schema->prefixItems) > 0) {
            $this->prefixItems = $schema->prefixItems;
        }
        $updateIfNotNull($schema->minItems, $this->minLength);
        $updateIfNotNull($schema->maxItems, $this->maxLength);
        $this->uniqueItems = $schema->uniqueItems;
        if ($schema->hasContains) {
            $this->hasContains = true;
            $this->contains = $schema->contains;
        }
        $updateIfNotNull($schema->minContains, $this->minContains);
        $updateIfNotNull($schema->maxContains, $this->maxContains);

        $updateIfNotNull($schema->minProperties, $this->minProperties);
        $updateIfNotNull($schema->maxProperties, $this->maxProperties);
        if (\count($schema->required) > 0) {
            $this->required = $schema->required;
        }
        if (\count($schema->dependentRequired) > 0) {
            $this->dependentRequired = $schema->dependentRequired;
        }

        $updateIfNotNull($schema->format, $this->format);

        $updateIfNotNull($schema->contentEncoding, $this->contentEncoding);
        $updateIfNotNull($schema->contentMediaType, $this->contentMediaType);
        $updateIfNotNull($schema->contentSchema, $this->contentSchema);
    }

    public function isEmpty(): bool
    {
        return
            null === $this->name
            && null === $this->title
            && null === $this->description
            && null === $this->defaultValue
            && false === $this->hasDefaultValue
            && false === $this->deprecated
            && false === $this->readOnly
            && false === $this->writeOnly

            && true === $this->additionalProperties
            && [] === $this->properties
            && [] === $this->patternProperties

            && [] === $this->oneOf
            && [] === $this->allOf
            && [] === $this->anyOf

            && [] === $this->type
            && [] === $this->enum
            && null === $this->constValue
            && false === $this->hasConstValue

            && null === $this->multipleOf
            && null === $this->minimum
            && null === $this->exclusiveMinimum
            && null === $this->maximum
            && null === $this->exclusiveMaximum

            && null === $this->minLength
            && null === $this->maxLength
            && null === $this->pattern

            && null === $this->items
            && [] === $this->prefixItems
            && null === $this->minItems
            && null === $this->maxItems
            && false === $this->uniqueItems
            && null === $this->contains
            && false === $this->hasContains
            && null === $this->minContains
            && null === $this->maxContains

            && null === $this->minProperties
            && null === $this->maxProperties
            && [] === $this->required
            && [] === $this->dependentRequired

            && null === $this->format

            && null === $this->contentEncoding
            && null === $this->contentMediaType
            && null === $this->contentSchema;
    }
}
