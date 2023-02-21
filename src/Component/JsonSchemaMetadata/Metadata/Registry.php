<?php

namespace Jane\Component\JsonSchemaMetadata\Metadata;

class Registry
{
    /**
     * @var array<string, string>
     */
    private array $jsonSource = [];

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

    public function addSource(string $path, string $contents): void
    {
        $this->jsonSource[$path] = $contents;
    }

    public function getSource(string $path): ?string
    {
        return $this->jsonSource[$path] ?? null;
    }

    /**
     * @return array<string, string>
     */
    public function getSources(): array
    {
        return $this->jsonSource;
    }
}
