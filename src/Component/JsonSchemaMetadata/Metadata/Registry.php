<?php

namespace Jane\Component\JsonSchemaMetadata\Metadata;

class Registry
{
    public const ROOT_ELEMENT = '#';

    /**
     * @var array<string, string>
     */
    private array $jsonSource = [];

    private ?string $currentSource = null;

    /**
     * @var array<string, JsonSchema>
     */
    private array $schemas = [];

    public function addSchema(string $path, JsonSchema $schema): void
    {
        $this->schemas[$path] = $schema;
    }

    public function hasSchema(string $path): bool
    {
        return \array_key_exists($path, $this->schemas);
    }

    public function getRoot(): ?JsonSchema
    {
        return $this->get(self::ROOT_ELEMENT);
    }

    public function get(string $path): ?JsonSchema
    {
        return $this->schemas[$path] ?? null;
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function all(): array
    {
        return $this->schemas;
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
