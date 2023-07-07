<?php

namespace Jane\Component\JsonSchemaMetadata;

use Jane\Component\JsonSchemaMetadata\NodeTraverser\JsonSchemaMetadataCallback;

class Configuration
{
    /**
     * @param JsonSchemaMetadataCallback[] $metadataCallbacks
     */
    public function __construct(
        public readonly array $metadataCallbacks = [],
    ) {
    }
}
