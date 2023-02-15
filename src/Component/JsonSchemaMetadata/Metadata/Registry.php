<?php

namespace Jane\Component\JsonSchemaMetadata\Metadata;

class Registry
{
    /**
     * @var JsonSchema[]
     */
    private array $schemas = [];

    public function addSchema(string $reference, JsonSchema $schema): void
    {
        $this->schemas[$reference] = $schema;
    }

    public function getRoot(): ?JsonSchema
    {
        return $this->get('#');
    }

    public function get(string $reference): ?JsonSchema
    {
        return $this->schemas[$reference] ?? null;
    }
}
