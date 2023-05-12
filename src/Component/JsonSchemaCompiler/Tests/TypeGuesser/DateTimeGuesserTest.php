<?php

namespace Jane\Component\JsonSchemaCompiler\Tests\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Type\DateTimeType;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\DateTimeGuesser;
use Jane\Component\JsonSchemaMetadata\Metadata\Format;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;

class DateTimeGuesserTest extends AbstractGuesserTester
{
    protected function setUp(): void
    {
        $this->guesser = new DateTimeGuesser();
        parent::setUp();
    }

    public static function providerData(): \Generator
    {
        yield [new JsonSchema(), null];

        $dateTimeType = new DateTimeType(\DateTimeInterface::ATOM, \DateTime::class, \DateTime::class);
        yield [new JsonSchema(type: Type::STRING, format: Format::DATE_TIME), $dateTimeType];
        yield [new JsonSchema(format: Format::DATE_TIME), $dateTimeType];
    }
}
