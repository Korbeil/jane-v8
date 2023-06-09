<?php

namespace Jane\Component\JsonSchemaGenerator;

use Jane\Component\JsonSchemaCompiler\Configuration as CompilerConfiguration;

class Configuration extends CompilerConfiguration
{
    public function __construct(
        public readonly string $outputDirectory,
        public readonly string $baseNamespace,
        public readonly bool $validation = true,
        public readonly bool $cleanGenerated = true,
        string $dateFormat = 'Y-m-d',
        string $dateTimeFormat = \DateTimeInterface::ATOM,
        string $dateUsedClass = \DateTime::class,
        string $dateTypedClass = \DateTime::class,
    ) {
        parent::__construct(
            $dateFormat,
            $dateTimeFormat,
            $dateUsedClass,
            $dateTypedClass,
        );
    }
}
