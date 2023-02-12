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
    public function testParser(): void
    {
        $parser = new Parser();
        $generatedOutput = $parser->parse(__DIR__.'/resources/schema.json');
        $expectedOutput = require_once __DIR__.'/resources/schema.php';

        self::assertEquals($expectedOutput, $generatedOutput);
    }

    public function testWrongFilePath(): void
    {
        $wrongPath = '/foo/bar/baz.json';

        try {
            $parser = new Parser();
            $parser->parse($wrongPath);
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
            $parser = new Parser();
            $parser->parse($unreadablePath);
        } catch (FileException $exception) {
            self::assertInstanceOf(CannotReadFileException::class, $exception);
            self::assertEquals($unreadablePath, $exception->getPath());
        }

        $filesystem->chmod($unreadablePath, 0644);
    }
}
