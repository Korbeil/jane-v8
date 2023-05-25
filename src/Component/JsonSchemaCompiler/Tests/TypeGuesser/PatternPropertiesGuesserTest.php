<?php

namespace Jane\Component\JsonSchemaCompiler\Tests\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Type\PatternMultipleType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type as CompiledType;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\PatternPropertiesGuesser;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;

class PatternPropertiesGuesserTest extends AbstractGuesserTester
{
    protected function setUp(): void
    {
        $this->guesser = new PatternPropertiesGuesser();
        parent::setUp();
    }

    public static function providerData(): \Generator
    {
        yield [new JsonSchema(), null];
        yield [new JsonSchema(patternProperties: []), null];
        yield [new JsonSchema(patternProperties: ['^cp_' => new JsonSchema(type: Type::INTEGER), 'city$' => new JsonSchema(type: Type::STRING)]), new PatternMultipleType(['^cp_' => new CompiledType(CompiledType::INTEGER), 'city$' => new CompiledType(CompiledType::STRING)])];
    }
}
