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

    public function testWithSimpleJsonSchema(): void
    {
        $registry = $this->collector->fromPath(__DIR__.'/resources/schema.json');

        self::assertInstanceOf(JsonSchema::class, $rootSchema = $registry->getRoot());
        self::assertCount(60, $rootSchema->properties);
        self::assertArrayHasKey('mouse', $rootSchema->properties);
        self::assertEquals(Type::BOOLEAN, $rootSchema->properties['mouse']->type);
        self::assertTrue($rootSchema->properties['mouse']->defaultValue);
        self::assertTrue($rootSchema->properties['mouse']->hasDefaultValue);
        self::assertArrayHasKey('statusline', $rootSchema->properties);
        self::assertEquals(Type::STRING, $rootSchema->properties['statusline']->type);
        self::assertEquals('sudo', $rootSchema->properties['statusline']->defaultValue);
        self::assertTrue($rootSchema->properties['statusline']->hasDefaultValue);
    }
}
