<?php

namespace Jane\Component\JsonSchemaCompiler\Tests\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type as CompiledType;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\SimpleTypeGuesser;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;

class SimpleTypeGuesserTest extends AbstractGuesserTester
{
    protected function setUp(): void
    {
        $this->guesser = new SimpleTypeGuesser();
        parent::setUp();
    }

    public static function providerData(): \Generator
    {
        yield [new JsonSchema(), null];
        yield [new JsonSchema(type: Type::BOOLEAN), new CompiledType(CompiledType::BOOLEAN)];
        yield [new JsonSchema(type: Type::INTEGER), new CompiledType(CompiledType::INTEGER)];
        yield [new JsonSchema(type: Type::NUMBER), new CompiledType(CompiledType::FLOAT)];
        yield [new JsonSchema(type: Type::STRING), new CompiledType(CompiledType::STRING)];
        yield [new JsonSchema(type: Type::NULL), new CompiledType(CompiledType::NULL)];
    }
}
