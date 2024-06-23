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
        yield [new JsonSchema(allOf: [new JsonSchema(name: 'Test', reference: '#/test', enum: [5, 10, 12, 15])]), new EnumType('TestEnum', ['VALUE5' => 5, 'VALUE10' => 10, 'VALUE12' => 12, 'VALUE15' => 15], CompiledType::INTEGER)];
        yield [new JsonSchema(allOf: [new JsonSchema(type: Type::INTEGER), new JsonSchema(name: 'Test', reference: '#/enum', enum: [5, 10, 12, 15]), new JsonSchema(reference: '#/minmax', minimum: 5, maximum: 15)]), new EnumType('Test1Enum', ['VALUE5' => 5, 'VALUE10' => 10, 'VALUE12' => 12, 'VALUE15' => 15], CompiledType::INTEGER)];
    }
}
