<?php

namespace Jane\Component\JsonSchemaMetadata\Tests;

use Jane\Component\JsonSchemaMetadata\Collector;
use Jane\Component\JsonSchemaMetadata\Exception\CannotReadFileException;
use Jane\Component\JsonSchemaMetadata\Exception\FileException;
use Jane\Component\JsonSchemaMetadata\Exception\FileNotFoundException;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

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
        $this->checkSchema($rootSchema);
    }

    public function testAnotherJsonSchema(): void
    {
        $registry = $this->collector->fromPath(__DIR__.'/resources/another-schema.json');

        self::assertInstanceOf(JsonSchema::class, $schema = $registry->getRoot());
        self::assertEquals('Longitude and Latitude Values', $schema->title);
        self::assertEquals([Type::OBJECT], $schema->type);
        self::assertCount(2, $schema->properties);
        self::assertEquals(['latitude', 'longitude'], $schema->required);
        self::assertEquals([Type::NUMBER], $schema->properties['latitude']->type);
        self::assertEquals(-90, $schema->properties['latitude']->minimum);
        self::assertEquals(90, $schema->properties['latitude']->maximum);
        self::assertEquals([Type::NUMBER], $schema->properties['longitude']->type);
        self::assertEquals(-180, $schema->properties['longitude']->minimum);
        self::assertEquals(180, $schema->properties['longitude']->maximum);
    }

    public function testJsonSchemaWithPointer(): void
    {
        $registry = $this->collector->fromPath(__DIR__.'/resources/schema-pointer.json');

        self::assertInstanceOf(JsonSchema::class, $rootSchema = $registry->getRoot());
        $this->checkSchema($rootSchema);
    }

    public function testJsonSchemaWithPointerOn2ndDepth(): void
    {
        $registry = $this->collector->fromPath(__DIR__.'/resources/schema-pointer-pointer.json');

        self::assertInstanceOf(JsonSchema::class, $rootSchema = $registry->getRoot());
        $this->checkSchema($rootSchema);
    }

    public function testPaintingSchema(): void
    {
        $registry = $this->collector->fromPath(__DIR__.'/resources/painting-schema.json');

        self::assertInstanceOf(JsonSchema::class, $schema = $registry->getRoot());
        self::assertEquals('Painting', $schema->title);
        self::assertEquals('Painting information', $schema->description);
        self::assertTrue($schema->additionalProperties);
        self::assertEquals(['name', 'artist', 'dimension', 'description', 'tags'], $schema->required);
        self::assertCount(5, $schema->properties);
        self::assertEquals([Type::STRING], $schema->properties['name']->type);
        self::assertEquals('Painting name', $schema->properties['name']->description);
        self::assertEquals([Type::STRING], $schema->properties['artist']->type);
        self::assertEquals(50, $schema->properties['artist']->maxLength);
        self::assertEquals('Name of the artist', $schema->properties['artist']->description);
        self::assertEquals([Type::STRING, Type::NULL], $schema->properties['description']->type);
        self::assertEquals('Painting description', $schema->properties['description']->description);
        self::assertEquals([Type::OBJECT], $schema->properties['dimension']->type);
        self::assertEquals('Painting dimension', $schema->properties['dimension']->title);
        self::assertEquals('Describes the dimension of a painting in cm', $schema->properties['dimension']->description);
        self::assertTrue($schema->properties['dimension']->additionalProperties);
        self::assertEquals(['width', 'height'], $schema->properties['dimension']->required);
        self::assertCount(2, $schema->properties['dimension']->properties);
        self::assertEquals([Type::NUMBER], $schema->properties['dimension']->properties['width']->type);
        self::assertEquals('Width of the product', $schema->properties['dimension']->properties['width']->description);
        self::assertEquals(1, $schema->properties['dimension']->properties['width']->minimum);
        self::assertEquals([Type::NUMBER], $schema->properties['dimension']->properties['height']->type);
        self::assertEquals('Height of the product', $schema->properties['dimension']->properties['height']->description);
        self::assertEquals(1, $schema->properties['dimension']->properties['height']->minimum);
        self::assertEquals([Type::ARRAY], $schema->properties['tags']->type);
        self::assertInstanceOf(JsonSchema::class, $schema->properties['tags']->items);
        self::assertEquals([Type::STRING], $schema->properties['tags']->items->type);
        self::assertEquals(['oil', 'watercolor', 'digital', 'famous'], $schema->properties['tags']->items->enum);
    }

    private function checkSchema(JsonSchema $schema): void
    {
        self::assertCount(60, $schema->properties);
        self::assertArrayHasKey('mouse', $schema->properties);
        self::assertEquals([Type::BOOLEAN], $schema->properties['mouse']->type);
        self::assertTrue($schema->properties['mouse']->defaultValue);
        self::assertTrue($schema->properties['mouse']->hasDefaultValue);
        self::assertEquals('Whether to enable mouse support
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options', $schema->properties['mouse']->description);
        self::assertArrayHasKey('statusline', $schema->properties);
        self::assertEquals([Type::STRING], $schema->properties['statusline']->type);
        self::assertEquals('sudo', $schema->properties['statusline']->defaultValue);
        self::assertTrue($schema->properties['statusline']->hasDefaultValue);
        self::assertArrayHasKey('tabsize', $schema->properties);
        self::assertEquals([Type::INTEGER], $schema->properties['tabsize']->type);
        self::assertEquals(4, $schema->properties['tabsize']->defaultValue);
        self::assertTrue($schema->properties['tabsize']->hasDefaultValue);
        self::assertEquals('A tab size
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options', $schema->properties['tabsize']->description);
    }

    public function testAllAnyOneOf(): void
    {
        $registry = $this->collector->fromPath(__DIR__.'/resources/allOf-schema.json');
        self::assertInstanceOf(JsonSchema::class, $schema = $registry->getRoot());
        self::assertEquals([Type::OBJECT], $schema->type);
        self::assertCount(2, $schema->allOf);
        self::assertEquals([Type::STRING], $schema->allOf[0]->type);
        self::assertEquals(5, $schema->allOf[1]->maxLength);

        $registry = $this->collector->fromPath(__DIR__.'/resources/anyOf-schema.json');
        self::assertInstanceOf(JsonSchema::class, $schema = $registry->getRoot());
        self::assertEquals([Type::OBJECT], $schema->type);
        self::assertCount(2, $schema->anyOf);
        self::assertEquals([Type::STRING], $schema->anyOf[0]->type);
        self::assertEquals(5, $schema->anyOf[0]->maxLength);
        self::assertEquals([Type::NUMBER], $schema->anyOf[1]->type);
        self::assertEquals(0, $schema->anyOf[1]->minimum);

        $registry = $this->collector->fromPath(__DIR__.'/resources/oneOf-schema.json');
        self::assertInstanceOf(JsonSchema::class, $schema = $registry->getRoot());
        self::assertEquals([Type::OBJECT], $schema->type);
        self::assertCount(2, $schema->oneOf);
        self::assertEquals([Type::NUMBER], $schema->oneOf[0]->type);
        self::assertEquals(5, $schema->oneOf[0]->multipleOf);
        self::assertEquals([Type::NUMBER], $schema->oneOf[1]->type);
        self::assertEquals(3, $schema->oneOf[1]->multipleOf);
    }

    public function testWrongFilePath(): void
    {
        $wrongPath = '/foo/bar/baz.json';

        try {
            $this->collector->fromPath($wrongPath);
        } catch (FileException $exception) {
            self::assertInstanceOf(FileNotFoundException::class, $exception);
            self::assertEquals($wrongPath, $exception->getPath());
        }
    }

    public function testUnreadableFile(): void
    {
        $filesystem = new Filesystem();

        $unreadablePath = __DIR__.'/resources/unreadable.json';
        $filesystem->chmod($unreadablePath, 0000);

        try {
            $this->collector->fromPath($unreadablePath);
        } catch (FileException $exception) {
            self::assertInstanceOf(CannotReadFileException::class, $exception);
            self::assertEquals($unreadablePath, $exception->getPath());
        }

        $filesystem->chmod($unreadablePath, 0644);
    }
}
