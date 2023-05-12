<?php

namespace Jane\Component\JsonSchemaCompiler\Tests\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Type\EnumType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type as CompiledType;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\AllOfGuesser;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;

class AllOfGuesserTest extends AbstractGuesserTester
{
    protected function setUp(): void
    {
        $this->guesser = new AllOfGuesser();
        parent::setUp();
    }

    public static function providerData(): \Generator
    {
        yield [new JsonSchema(), null];
        yield [new JsonSchema(allOf: []), null];
        yield [new JsonSchema(allOf: [new JsonSchema(type: Type::STRING)]), new CompiledType(CompiledType::STRING)];
        yield [new JsonSchema(allOf: [new JsonSchema(type: Type::STRING), new JsonSchema(maxLength: 5)]), new CompiledType(CompiledType::STRING)];
        yield [new JsonSchema(allOf: [new JsonSchema(type: Type::INTEGER), new JsonSchema(enum: [5, 10, 12, 15]), new JsonSchema(minimum: 5, maximum: 15)]), new EnumType([5, 10, 12, 15], CompiledType::INTEGER)];
    }
}
