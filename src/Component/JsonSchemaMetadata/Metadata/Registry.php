<?php

namespace Jane\Component\JsonSchemaMetadata\Metadata;

class Registry
{
    public const ROOT_ELEMENT = '#';

    /**
     * @var array<string, string>
     */
    private array $jsonSource = [];

    private null|string $currentSource = null;

    /**
     * @var JsonSchema[]
     */
    private array $schemas = [];

    public function addSchema(string $path, JsonSchema $schema): void
    {
        $this->schemas[$path] = $schema;
    }

    public function getRoot(): ?JsonSchema
    {
        return $this->get(self::ROOT_ELEMENT);
    }

    public function get(string $path): ?JsonSchema
    {
        return $this->schemas[$path] ?? null;
    }

    public function addSource(string $path, string $contents): void
    {
        $this->jsonSource[$path] = $contents;
    }

    public function getSource(string $path): ?string
    {
        return $this->jsonSource[$path] ?? null;
    }

    public function currentSource(string $path = null): ?string
    {
        if (null !== $path) {
            $this->currentSource = $path;
        }

        return $this->currentSource;
    }
}
