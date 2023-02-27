<?php

namespace Jane\Component\JsonSchemaMetadata;

use Jane\Component\JsonSchemaMetadata\Metadata\Registry;

interface CollectorInterface
{
    /**
     * Collect a JSON Schema metadata from extracted data into PHP objects.
     *
     * @param JsonSchemaDefinition $data    Data to collect from
     * @param array<string, mixed> $context
     */
    public function fromParsed(mixed $data, array $context = []): Registry;

    /**
     * Collect a JSON Schema metadata from JSON file into PHP objects.
     *
     * @param string               $path    Path to the file where your JSON Schema is
     * @param array<string, mixed> $context
     */
    public function fromPath(string $path, array $context = []): Registry;
}
