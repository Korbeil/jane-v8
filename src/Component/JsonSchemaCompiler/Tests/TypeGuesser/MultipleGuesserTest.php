<?php

namespace Jane\Component\JsonSchemaCompiler\Tests\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Type\MultipleType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type as CompiledType;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\MultipleGuesser;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;

class MultipleGuesserTest extends AbstractGuesserTester
{
    protected function setUp(): void
    {
        $this->guesser = new MultipleGuesser();
        parent::setUp();
    }

    public static function providerData(): \Generator
    {
        yield [new JsonSchema(), null];
        yield [new JsonSchema(type: [Type::STRING, Type::NULL]), new MultipleType([new CompiledType(CompiledType::STRING), new CompiledType(CompiledType::NULL)])];
        yield [new JsonSchema(type: [Type::STRING, Type::INTEGER, Type::NULL]), new MultipleType([new CompiledType(CompiledType::STRING), new CompiledType(CompiledType::INTEGER), new CompiledType(CompiledType::NULL)])];
        yield [new JsonSchema(type: [Type::STRING, Type::INTEGER]), new MultipleType([new CompiledType(CompiledType::STRING), new CompiledType(CompiledType::INTEGER)])];
    }
}
