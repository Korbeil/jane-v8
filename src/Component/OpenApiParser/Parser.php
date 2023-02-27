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

    public function fromFile(string $path): mixed
    {
        $fileExtension = strtolower(pathinfo(basename($path), \PATHINFO_EXTENSION));

        return $this->fromString($this->load($path), $fileExtension);
    }

    public function fromString(string $contents, string $fileExtension): mixed
    {
        if (\in_array($fileExtension, ['yaml', 'yml'], true)) {
            return Yaml::parse($contents, Yaml::PARSE_DATETIME | Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE);
        } elseif ('json' === $fileExtension) {
            return $this->encoder->decode($contents, JsonEncoder::FORMAT);
        }

        throw new UnsupportedFileFormatException(sprintf('File extension "%s" not supported.', $fileExtension), fileExtension: $fileExtension);
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
}
