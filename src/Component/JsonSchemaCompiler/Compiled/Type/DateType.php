<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled\Type;

class DateType extends ObjectType
{
    public function __construct(
        public readonly string $format = 'Y-m-d',
        public readonly string $usedClass = \DateTime::class,
        public readonly string $typedClass = \DateTime::class,
    ) {
        parent::__construct($usedClass, false);
    }
}
