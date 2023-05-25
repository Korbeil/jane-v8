<?php

namespace Jane\Component\JsonSchemaCompiler\Tests\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type as CompiledType;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\AdditionalItemsGuesser;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;

class AdditionalItemsGuesserTest extends AbstractGuesserTester
{
    protected function setUp(): void
    {
        $this->guesser = new AdditionalItemsGuesser();
        parent::setUp();
    }

    public static function providerData(): \Generator
    {
        yield [new JsonSchema(), null];
        yield [new JsonSchema(additionalItems: null), null];
        yield [new JsonSchema(additionalItems: new JsonSchema(type: Type::BOOLEAN)), new CompiledType(CompiledType::BOOLEAN)];
        yield [new JsonSchema(additionalItems: new JsonSchema(type: Type::STRING, maxLength: 5)), new CompiledType(CompiledType::STRING)];
    }
}
