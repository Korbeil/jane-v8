<?php

namespace Jane\Component\JsonSchemaMetadata\Tests;

use Jane\Component\JsonSchemaMetadata\Collector;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;
use PHPUnit\Framework\TestCase;

class CollectorTest extends TestCase
{
    private Collector $collector;

    protected function setUp(): void
    {
        $this->collector = new Collector();
    }

    public function testSimpleJsonSchema(): void
    {
        $registry = $this->collector->fromPath(__DIR__.'/resources/schema.json');

        self::assertInstanceOf(JsonSchema::class, $rootSchema = $registry->getRoot());
        self::assertCount(60, $rootSchema->properties);
        self::assertArrayHasKey('mouse', $rootSchema->properties);
        self::assertEquals(Type::BOOLEAN, $rootSchema->properties['mouse']->type);
        self::assertTrue($rootSchema->properties['mouse']->defaultValue);
        self::assertTrue($rootSchema->properties['mouse']->hasDefaultValue);
        self::assertEquals('Whether to enable mouse support
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options', $rootSchema->properties['mouse']->description);
        self::assertArrayHasKey('statusline', $rootSchema->properties);
        self::assertEquals(Type::STRING, $rootSchema->properties['statusline']->type);
        self::assertEquals('sudo', $rootSchema->properties['statusline']->defaultValue);
        self::assertTrue($rootSchema->properties['statusline']->hasDefaultValue);
    }

    public function testJsonSchemaWithPointer(): void
    {
        $registry = $this->collector->fromPath(__DIR__.'/resources/schema-pointer.json');

        self::assertInstanceOf(JsonSchema::class, $rootSchema = $registry->getRoot());
        self::assertCount(60, $rootSchema->properties);
        self::assertArrayHasKey('mouse', $rootSchema->properties);
        self::assertEquals(Type::BOOLEAN, $rootSchema->properties['mouse']->type);
        self::assertTrue($rootSchema->properties['mouse']->defaultValue);
        self::assertTrue($rootSchema->properties['mouse']->hasDefaultValue);
        self::assertEquals('Whether to enable mouse support
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options', $rootSchema->properties['mouse']->description);
        self::assertArrayHasKey('statusline', $rootSchema->properties);
        self::assertEquals(Type::STRING, $rootSchema->properties['statusline']->type);
        self::assertEquals('sudo', $rootSchema->properties['statusline']->defaultValue);
        self::assertTrue($rootSchema->properties['statusline']->hasDefaultValue);
    }

    //  @fixme test FileNotFoundException
    //  @fixme test CannotReadFileException
}
