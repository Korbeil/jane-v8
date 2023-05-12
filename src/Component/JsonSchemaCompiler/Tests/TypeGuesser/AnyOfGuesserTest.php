<?php

namespace Jane\Component\JsonSchemaCompiler\Tests\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Type\ArrayType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\MultipleType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type as CompiledType;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\AnyOfGuesser;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;

class AnyOfGuesserTest extends AbstractGuesserTester
{
    protected function setUp(): void
    {
        $this->guesser = new AnyOfGuesser();
        parent::setUp();
    }

    public static function providerData(): \Generator
    {
        // empty
        yield [new JsonSchema(), null];

        // one
        yield [new JsonSchema(anyOf: [new JsonSchema(type: Type::STRING)]), new CompiledType(CompiledType::STRING)];
        yield [new JsonSchema(anyOf: [new JsonSchema(type: Type::BOOLEAN)]), new CompiledType(CompiledType::BOOLEAN)];
        yield [
            new JsonSchema(anyOf: [new JsonSchema(type: Type::ARRAY, items: new JsonSchema(type: Type::STRING))]),
            new ArrayType(new CompiledType(CompiledType::STRING)),
        ];

        // multiple
        yield [
            new JsonSchema(anyOf: [new JsonSchema(type: Type::STRING), new JsonSchema(type: Type::NULL)]),
            new MultipleType([new CompiledType(CompiledType::STRING), new CompiledType(CompiledType::NULL)]),
        ];

        yield [
            new JsonSchema(anyOf: [new JsonSchema(type: Type::STRING), new JsonSchema(type: Type::ARRAY, items: new JsonSchema(type: Type::STRING)), new JsonSchema(type: Type::NULL)]),
            new MultipleType([new CompiledType(CompiledType::STRING), new ArrayType(new CompiledType(CompiledType::STRING)), new CompiledType(CompiledType::NULL)]),
        ];
    }
}
