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
