<?php

namespace Jane\Component\JsonSchemaParser;

use Jane\Component\JsonSchemaParser\Exception\CannotReadFileException;
use Jane\Component\JsonSchemaParser\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class Parser implements ParserInterface
{
    private readonly JsonEncoder $encoder;

    public function __construct(
    ) {
        $this->encoder = new JsonEncoder(defaultContext: [JsonDecode::ASSOCIATIVE => true]);
    }

    public function parse(string $path): mixed
    {
        return $this->fromString($this->load($path));
    }

    public function fromString(string $contents): mixed
    {
        return $this->encoder->decode($contents, JsonEncoder::FORMAT);
    }

    private function load(string $path): string
    {
        $filesystem = new Filesystem();
        if (!$filesystem->exists($path)) {
            throw new FileNotFoundException(sprintf('File "%s" not found.', $path), path: $path);
        }

        $fileContents = @file_get_contents($path);

        if (false === $fileContents) {
            throw new CannotReadFileException(sprintf('Cannot read file "%s".', $path), path: $path);
        }

        return $fileContents;
    }
}
