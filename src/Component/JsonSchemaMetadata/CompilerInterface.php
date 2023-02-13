<?php

namespace Jane\Component\JsonSchemaMetadata;

use Jane\Component\JsonSchemaMetadata\Metadata\Registry;

interface CompilerInterface
{
    /**
     * Compile a JSON Schema file into JSON Schema metadata.
     *
     * @param string $path File to compile
     */
    public function compile(string $path): Registry;
}
