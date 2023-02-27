<?php

namespace Jane\Component\OpenApiParser;

use Jane\Component\OpenApiParser\Exception\CannotReadFileException;
use Jane\Component\OpenApiParser\Exception\FileNotFoundException;
use Jane\Component\OpenApiParser\Exception\UnsupportedFileFormatException;

interface ParserInterface
{
    /**
     * Parse an OpenAPI file contents into PHP array.
     *
     * @param string $path File to parse
     *
     * @throws FileNotFoundException
     * @throws CannotReadFileException
     * @throws UnsupportedFileFormatException
     */
    public function fromFile(string $path): mixed;

    /**
     * Parse a OpenAPI contents into PHP array.
     *
     * @param string $contents      Contents to parse
     * @param string $fileExtension Type of the contents to parse
     */
    public function fromString(string $contents, string $fileExtension): mixed;
}
