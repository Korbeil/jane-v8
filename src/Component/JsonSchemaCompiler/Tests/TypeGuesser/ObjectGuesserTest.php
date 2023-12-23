<?php

namespace Jane\Component\JsonSchemaCompiler\Tests\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Type\ObjectType;
use Jane\Component\JsonSchemaCompiler\Exception\NoSchemaNameException;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\ObjectGuesser;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;

class ObjectGuesserTest extends AbstractGuesserTester
{
    protected function setUp(): void
    {
        $this->guesser = new ObjectGuesser();
        parent::setUp();
    }

    public static function providerData(): \Generator
    {
        yield [new JsonSchema(), null];
        yield [new JsonSchema(name: 'Test', properties: ['foo' => new JsonSchema(name: 'foo', type: Type::STRING)], type: Type::OBJECT), new ObjectType('Test')];
    }

    public function testNoSchemaName(): void
    {
        self::expectException(NoSchemaNameException::class);

        $this->guesser->guessType($this->registry, new JsonSchema(properties: ['foo' => new JsonSchema(name: 'foo', type: Type::STRING)], type: Type::OBJECT));
    }
}
