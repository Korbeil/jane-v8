<?php

namespace Jane\Component\OpenApiParser;

use Jane\Component\OpenApiParser\Exception\CannotReadFileException;
use Jane\Component\OpenApiParser\Exception\FileNotFoundException;
use Jane\Component\OpenApiParser\Exception\UnsupportedFileFormatException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Yaml\Yaml;

class Parser implements ParserInterface
{
    private readonly JsonEncoder $encoder;
    private readonly Filesystem $filesystem;

    public function __construct(
    ) {
        $this->encoder = new JsonEncoder(defaultContext: [JsonDecode::ASSOCIATIVE => true]);
        $this->filesystem = new Filesystem();
    }

    public function parse(string $path): mixed
    {
        return $this->decode($this->load($path), $path);
    }

    private function load(string $path): string
    {
        if (!$this->filesystem->exists($path)) {
            throw new FileNotFoundException(sprintf('File "%s" not found.', $path), path: $path);
        }

        $fileContents = @file_get_contents($path);

        if (false === $fileContents) {
            throw new CannotReadFileException(sprintf('Cannot read file "%s".', $path), path: $path);
        }

        return $fileContents;
    }

    private function decode(string $fileContents, string $path): mixed
    {
        $fileExtension = strtolower(pathinfo(basename($path), \PATHINFO_EXTENSION));

        if (\in_array($fileExtension, ['yaml', 'yml'], true)) {
            return Yaml::parse($fileContents, Yaml::PARSE_DATETIME | Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE);
        } elseif ('json' === $fileExtension) {
            return $this->encoder->decode($fileContents, JsonEncoder::FORMAT);
        }

        throw new UnsupportedFileFormatException(sprintf('File extension "%s" for path "%s" not supported.', $fileExtension, $path), path: $path, fileExtension: $fileExtension);
    }
}
