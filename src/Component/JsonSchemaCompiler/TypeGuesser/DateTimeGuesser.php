<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\DateTimeType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaMetadata\Metadata\Format;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type as MetadataType;

class DateTimeGuesser implements TypeGuesserInterface
{
    public function __construct(
        private readonly string $dateFormat = \DateTimeInterface::ATOM,
        private readonly string $usedClass = \DateTime::class,
        private readonly string $typedClass = \DateTime::class,
    ) {
    }

    public function guessType(Registry $registry, JsonSchema $schema): ?Type
    {
        if (([] === $schema->type || [MetadataType::STRING] === $schema->type)
            && Format::DATE_TIME === $schema->format) {
            return new DateTimeType($this->dateFormat, $this->usedClass, $this->typedClass);
        }

        return null;
    }
}
