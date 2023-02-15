<?php

namespace Jane\Component\JsonSchemaMetadata;

use Jane\Component\JsonSchemaMetadata\Metadata\Registry;

interface CollectorInterface
{
    /**
     * Collect a JSON Schema metadata from extracted data into PHP objects.
     *
     * @param JsonSchemaDefinition $data Data to collect from
     */
    public function collect(mixed $data): Registry;

    /**
     * Collect a JSON Schema metadata from JSON file into PHP objects.
     */
    public function fromPath(string $path): Registry;
}
