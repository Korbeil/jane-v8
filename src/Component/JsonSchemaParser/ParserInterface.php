<?php

namespace Jane\Component\JsonSchemaParser;

use Jane\Component\JsonSchemaParser\Exception\CannotReadFileException;
use Jane\Component\JsonSchemaParser\Exception\FileNotFoundException;

interface ParserInterface
{
    /**
     * Parse a JSON Schema file contents into PHP array.
     *
     * @param string $path File to parse
     *
     * @throws FileNotFoundException
     * @throws CannotReadFileException
     */
    public function fromPath(string $path): mixed;

    /**
     * Parse a JSON Schema file contents into PHP array.
     *
     * @param string $contents Contents to parse
     */
    public function fromString(string $contents): mixed;
}
