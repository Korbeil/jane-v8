<?php

namespace Jane\Component\JsonSchemaMetadata;

use Jane\Component\JsonSchemaMetadata\Metadata\Registry;

interface CollectorInterface
{
    /**
     * Collect a JSON Schema metadata from a file into PHP objects.
     *
     * @param string $path File to collect from
     */
    public function collect(string $path): Registry;
}
