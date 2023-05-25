<?php

namespace Jane\Component\JsonSchemaCompiler;

class Configuration
{
    public function __construct(
        public readonly string $dateFormat = 'Y-m-d',
        public readonly string $dateTimeFormat = \DateTimeInterface::ATOM,
        public readonly string $dateUsedClass = \DateTime::class,
        public readonly string $dateTypedClass = \DateTime::class,
    ) {
    }
}
