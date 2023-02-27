<?php

namespace Jane\Component\OpenApiParser\Tests;

use Jane\Component\OpenApiParser\Exception\CannotReadFileException;
use Jane\Component\OpenApiParser\Exception\FileException;
use Jane\Component\OpenApiParser\Exception\FileNotFoundException;
use Jane\Component\OpenApiParser\Exception\UnsupportedFileFormatException;
use Jane\Component\OpenApiParser\Parser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class ParserTest extends TestCase
{
    private Parser $parser;

    protected function setUp(): void
    {
        $this->parser = new Parser();
    }

    public function testParserOnJsonFile(): void
    {
        $generatedOutput = $this->parser->fromFile(__DIR__.'/resources/schema.json');
        $expectedOutput = require __DIR__.'/resources/schema.php';

        self::assertEquals($expectedOutput, $generatedOutput);
    }

    public function testParserOnSimpleYamlFile(): void
    {
        $generatedOutput = $this->parser->fromFile(__DIR__.'/resources/simple-schema.yaml');
        $expectedOutput = require __DIR__.'/resources/schema.php';

        self::assertEquals($expectedOutput, $generatedOutput);
    }

    public function testParserOnYmlFile(): void
    {
        $generatedOutput = $this->parser->fromFile(__DIR__.'/resources/schema.yml');
        $expectedOutput = require __DIR__.'/resources/schema.php';

        self::assertEquals($expectedOutput, $generatedOutput);
    }

    public function testParserOnYamlWithAnchorsFile(): void
    {
        $generatedOutput = $this->parser->fromFile(__DIR__.'/resources/schema-with-anchors.yaml');
        $expectedOutput = require __DIR__.'/resources/schema.php';

        self::assertEquals($expectedOutput, $generatedOutput);
    }

    public function testWrongFilePath(): void
    {
        $wrongPath = '/foo/bar/baz.json';

        try {
            $this->parser->fromFile($wrongPath);
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
            $this->parser->fromFile($unreadablePath);
        } catch (FileException $exception) {
            self::assertInstanceOf(CannotReadFileException::class, $exception);
            self::assertEquals($unreadablePath, $exception->getPath());
        }

        $filesystem->chmod($unreadablePath, 0644);
    }

    public function testWrongFileExtension(): void
    {
        $wrongFileExtension = __DIR__.'/resources/schema.js';

        try {
            $this->parser->fromFile($wrongFileExtension);
        } catch (FileException $exception) {
            self::assertInstanceOf(UnsupportedFileFormatException::class, $exception);
            self::assertEquals('js', $exception->getFileExtension());
        }
    }
}
