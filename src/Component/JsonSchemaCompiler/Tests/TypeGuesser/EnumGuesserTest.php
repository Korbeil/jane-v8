<?php

namespace Jane\Component\JsonSchemaCompiler\Tests\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Type\EnumType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type as CompiledType;
use Jane\Component\JsonSchemaCompiler\Exception\EnumMultipleTypesFoundException;
use Jane\Component\JsonSchemaCompiler\Exception\EnumNoTypeFoundException;
use Jane\Component\JsonSchemaCompiler\Exception\EnumTypeMissmatchException;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\EnumGuesser;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;

class EnumGuesserTest extends AbstractGuesserTester
{
    protected function setUp(): void
    {
        $this->guesser = new EnumGuesser();
        parent::setUp();
    }

    public static function providerData(): \Generator
    {
        yield [new JsonSchema(), null];

        $values = ['foo', 'bar', 'baz'];
        yield [new JsonSchema(enum: $values), new EnumType($values, CompiledType::STRING)];
        yield [new JsonSchema(type: Type::STRING, enum: $values), new EnumType($values, CompiledType::STRING)];
    }

    public function testNoTypeEnum(): void
    {
        self::expectException(EnumNoTypeFoundException::class);

        // we made an error on purpose here, so ignoring next line for PHPStan analysis
        // @phpstan-ignore-next-line
        $schema = new JsonSchema(enum: [['a'], 'b', 'c']);
        $this->guesser->guessType($this->registry, $schema);
    }

    public function testMultipleTypesEnum(): void
    {
        self::expectException(EnumMultipleTypesFoundException::class);

        $schema = new JsonSchema(enum: ['a', 1, 1.7]);
        $this->guesser->guessType($this->registry, $schema);
    }

    public function testTypeMissmatchEnum(): void
    {
        self::expectException(EnumTypeMissmatchException::class);

        $schema = new JsonSchema(type: Type::STRING, enum: [1, 2, 3]);
        $this->guesser->guessType($this->registry, $schema);
    }
}
