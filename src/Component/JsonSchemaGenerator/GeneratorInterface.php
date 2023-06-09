<?php

namespace Jane\Component\JsonSchemaGenerator;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;

interface GeneratorInterface
{
    public function fromPath(string $path): void;

    public function fromRegistry(Registry $registry): void;
}
