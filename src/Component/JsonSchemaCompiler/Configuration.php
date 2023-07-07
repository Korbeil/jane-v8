<?php

namespace Jane\Component\JsonSchemaCompiler;

use Jane\Component\JsonSchemaMetadata\Configuration as MetadataConfiguration;
use Jane\Component\JsonSchemaMetadata\NodeTraverser\JsonSchemaMetadataCallback;

class Configuration extends MetadataConfiguration
{
    /**
     * @param JsonSchemaMetadataCallback[] $metadataCallbacks
     */
    public function __construct(
        public readonly string $dateFormat = 'Y-m-d',
        public readonly string $dateTimeFormat = \DateTimeInterface::ATOM,
        public readonly string $dateUsedClass = \DateTime::class,
        public readonly string $dateTypedClass = \DateTime::class,
        array $metadataCallbacks = [],
    ) {
        parent::__construct($metadataCallbacks);
    }
}
