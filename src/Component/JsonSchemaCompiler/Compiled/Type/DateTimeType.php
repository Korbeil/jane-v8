<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled\Type;

class DateTimeType extends ObjectType
{
    public function __construct(
        public string $format = \DateTimeInterface::ATOM,
        public readonly string $usedClass = \DateTime::class,
        public readonly string $typedClass = \DateTime::class,
    ) {
        parent::__construct($usedClass, false);
    }

    public function isA(string $type): bool
    {
        return 'date-time' === $type;
    }
}
