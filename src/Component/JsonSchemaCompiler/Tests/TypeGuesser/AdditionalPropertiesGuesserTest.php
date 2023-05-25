<?php

namespace Jane\Component\JsonSchemaCompiler\Tests\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Type\MapType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type as CompiledType;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\AdditionalPropertiesGuesser;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;

class AdditionalPropertiesGuesserTest extends AbstractGuesserTester
{
    protected function setUp(): void
    {
        $this->guesser = new AdditionalPropertiesGuesser();
        parent::setUp();
    }

    public static function providerData(): \Generator
    {
        yield [new JsonSchema(additionalProperties: false), null];
        yield [new JsonSchema(), new MapType(new CompiledType(CompiledType::MIXED))];
        yield [new JsonSchema(additionalProperties: true), new MapType(new CompiledType(CompiledType::MIXED))];
        yield [new JsonSchema(additionalProperties: new JsonSchema(type: Type::BOOLEAN)), new MapType(new CompiledType(CompiledType::BOOLEAN))];
        yield [new JsonSchema(additionalProperties: new JsonSchema(type: Type::STRING, maxLength: 5)), new MapType(new CompiledType(CompiledType::STRING))];
    }
}
