<?php

namespace Jane\Component\JsonSchemaMetadata\Metadata;

class JsonSchema
{
    public function __construct(
        // Vocabulary for Basic Meta-Data Annotations
        public readonly ?string $title = null,
        public readonly ?string $description = null,
        public readonly mixed $defaultValue = null,
        public readonly bool $hasDefaultValue = false,
        public readonly bool $deprecated = false,
        public readonly bool $readOnly = false,
        public readonly bool $writeOnly = false,

        // Core keywords
        public readonly bool $additionalProperties = true,
        /** @var array<string, self> $properties */
        public readonly array $properties = [],

        // Keywords for Any Instance Type
        public readonly Type $type = Type::OBJECT,
        /** @var mixed[] $enum */
        public readonly array $enum = [],
        public readonly mixed $constValue = null,
        public readonly bool $hasConstValue = false,

        // Keywords for Numeric Instances (number and integer)
        public readonly ?int $multipleOf = null,
        public readonly ?int $minimum = null,
        public readonly ?int $exclusiveMinimum = null,
        public readonly ?int $maximum = null,
        public readonly ?int $exclusiveMaximum = null,

        // Keywords for Strings
        public readonly ?int $minLength = null,
        public readonly ?int $maxLength = null,
        public readonly ?string $pattern = null,

        // Keywords for Arrays
        public readonly ?int $minItems = null,
        public readonly ?int $maxItems = null,
        public readonly bool $uniqueItems = false,
        public readonly mixed $contains = null,
        public readonly bool $hasContains = false,
        public readonly ?int $minContains = null,
        public readonly ?int $maxContains = null,

        // Keywords for Objects
        public readonly ?int $minProperties = null,
        public readonly ?int $maxProperties = null,
        /** @var string[] $required */
        public readonly array $required = [],
        /** @var array<string, string[]> $dependentRequired */
        public readonly array $dependentRequired = [],

        // Vocabularies for Semantic Content With "format"
        public readonly ?Format $format = null,

        // Vocabulary for the Contents of String-Encoded Data
        public readonly ?string $contentEncoding = null,
        public readonly ?string $contentMediaType = null,
        public readonly ?self $contentSchema = null,
    ) {
    }
}
