<?php

namespace Jane\Component\OpenApiParser;

use Jane\Component\OpenApiParser\Exception\CannotReadFileException;
use Jane\Component\OpenApiParser\Exception\FileNotFoundException;

interface ParserInterface
{
    /**
     * Parse an OpenAPI file contents into PHP array.
     *
     * @param string $path File to parse
     *
     * @throws FileNotFoundException
     * @throws CannotReadFileException
     */
    public function parse(string $path): mixed;
}
