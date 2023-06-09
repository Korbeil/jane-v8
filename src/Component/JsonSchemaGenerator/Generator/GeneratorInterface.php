<?php

namespace Jane\Component\JsonSchemaGenerator\Generator;

use Jane\Component\JsonSchemaCompiler\Compiled\Model;
use Jane\Component\JsonSchemaGenerator\Printer\Registry;

interface GeneratorInterface
{
    public function generate(Registry $registry, Model $model): void;
}
