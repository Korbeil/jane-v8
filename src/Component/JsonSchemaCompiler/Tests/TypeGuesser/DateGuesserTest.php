<?php

namespace Jane\Component\JsonSchemaCompiler\Tests\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Type\DateType;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\DateGuesser;
use Jane\Component\JsonSchemaMetadata\Metadata\Format;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;

class DateGuesserTest extends AbstractGuesserTester
{
    protected function setUp(): void
    {
        $this->guesser = new DateGuesser();
        parent::setUp();
    }

    public static function providerData(): \Generator
    {
        yield [new JsonSchema(), null];

        $dateType = new DateType('Y-m-d', \DateTime::class, \DateTime::class);
        yield [new JsonSchema(type: Type::STRING, format: Format::DATE), $dateType];
        yield [new JsonSchema(format: Format::DATE), $dateType];
    }
}
