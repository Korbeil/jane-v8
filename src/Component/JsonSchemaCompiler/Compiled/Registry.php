<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled;

use Jane\Component\JsonSchemaMetadata\Metadata\Registry as MetadataRegistry;

class Registry
{
    public function __construct(
        public readonly ?MetadataRegistry $metadataRegistry = null,
    ) {
    }
}
