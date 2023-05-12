<?php

namespace Jane\Component\JsonSchemaCompiler\Tests\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Type\ArrayType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\MultipleType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type as CompiledType;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\ArrayGuesser;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;

class ArrayGuesserTest extends AbstractGuesserTester
{
    protected function setUp(): void
    {
        $this->guesser = new ArrayGuesser();
        parent::setUp();
    }

    public static function providerData(): \Generator
    {
        // invalid
        yield [new JsonSchema(), null];

        // no items
        yield [new JsonSchema(type: Type::ARRAY), new ArrayType(new CompiledType(CompiledType::MIXED))];

        // with items and simple schema
        yield [
            new JsonSchema(type: Type::ARRAY, items: new JsonSchema(type: Type::STRING)),
            new ArrayType(new CompiledType(CompiledType::STRING)),
        ];

        yield [
            new JsonSchema(type: Type::ARRAY, items: new JsonSchema(type: Type::INTEGER)),
            new ArrayType(new CompiledType(CompiledType::INTEGER)),
        ];

        // with enurable items
        yield [
            new JsonSchema(type: Type::ARRAY, items: [new JsonSchema(type: Type::STRING), new JsonSchema(type: Type::INTEGER)]),
            new MultipleType([new CompiledType(CompiledType::STRING), new CompiledType(CompiledType::INTEGER)]),
        ];
    }
}
