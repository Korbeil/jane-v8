<?php

namespace Jane\Component\JsonSchemaCompiler\Tests\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Type\MultipleType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type as CompiledType;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\OneOfGuesser;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;

class OneOfGuesserTest extends AbstractGuesserTester
{
    protected function setUp(): void
    {
        $this->guesser = new OneOfGuesser();
        parent::setUp();
    }

    public static function providerData(): \Generator
    {
        yield [new JsonSchema(), null];
        yield [new JsonSchema(oneOf: []), null];
        yield [new JsonSchema(oneOf: [new JsonSchema(type: Type::STRING)]), new CompiledType(CompiledType::STRING)];
        yield [new JsonSchema(oneOf: [new JsonSchema(type: Type::STRING), new JsonSchema(type: Type::INTEGER)]), new MultipleType([new CompiledType(CompiledType::STRING), new CompiledType(CompiledType::INTEGER)])];
    }
}
