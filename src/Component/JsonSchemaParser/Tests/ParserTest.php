<?php

namespace Jane\Component\JsonSchemaParser\Tests;

use Jane\Component\JsonSchemaParser\Exception\CannotReadFileException;
use Jane\Component\JsonSchemaParser\Exception\FileException;
use Jane\Component\JsonSchemaParser\Exception\FileNotFoundException;
use Jane\Component\JsonSchemaParser\Parser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class ParserTest extends TestCase
{
    private Parser $parser;

    protected function setUp(): void
    {
        $this->parser = new Parser();
    }

    public function testParser(): void
    {
        $generatedOutput = $this->parser->parse(__DIR__.'/resources/schema.json');
        $expectedOutput = require __DIR__.'/resources/schema.php';

        self::assertEquals($expectedOutput, $generatedOutput);
    }

    public function testWrongFilePath(): void
    {
        $wrongPath = '/foo/bar/baz.json';

        try {
            $this->parser->parse($wrongPath);
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
            $this->parser->parse($unreadablePath);
        } catch (FileException $exception) {
            self::assertInstanceOf(CannotReadFileException::class, $exception);
            self::assertEquals($unreadablePath, $exception->getPath());
        }

        $filesystem->chmod($unreadablePath, 0644);
    }

    public function testFromString(): void
    {
        /** @var string $fileContents */
        $fileContents = file_get_contents(__DIR__.'/resources/schema.json');

        $generatedOutput = $this->parser->fromString($fileContents);
        $expectedOutput = require __DIR__.'/resources/schema.php';

        self::assertEquals($expectedOutput, $generatedOutput);
    }
}
