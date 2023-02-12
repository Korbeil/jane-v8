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
    public function parse(string $path): mixed;
}
