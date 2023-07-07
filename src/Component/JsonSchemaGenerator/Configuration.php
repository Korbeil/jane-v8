<?php

namespace Jane\Component\JsonSchemaGenerator;

use Jane\Component\JsonSchemaCompiler\Configuration as CompilerConfiguration;
use Jane\Component\JsonSchemaMetadata\NodeTraverser\JsonSchemaMetadataCallback;

class Configuration extends CompilerConfiguration
{
    /**
     * @param JsonSchemaMetadataCallback[] $metadataCallbacks
     */
    public function __construct(
        public readonly string $outputDirectory,
        public readonly string $baseNamespace,
        public readonly bool $validation = true,
        public readonly bool $cleanGenerated = true,
        public readonly bool $useFixer = false,
        public readonly ?string $fixerConfig = null,
        string $dateFormat = 'Y-m-d',
        string $dateTimeFormat = \DateTimeInterface::ATOM,
        string $dateUsedClass = \DateTime::class,
        string $dateTypedClass = \DateTime::class,
        array $metadataCallbacks = [],
    ) {
        parent::__construct(
            $dateFormat,
            $dateTimeFormat,
            $dateUsedClass,
            $dateTypedClass,
            $metadataCallbacks,
        );
    }
}
