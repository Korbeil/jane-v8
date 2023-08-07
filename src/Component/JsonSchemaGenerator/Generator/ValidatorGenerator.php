<?php

namespace Jane\Component\JsonSchemaGenerator\Generator;

use Jane\Component\JsonSchemaCompiler\Compiled\Model;
use Jane\Component\JsonSchemaGenerator\Configuration;
use Jane\Component\JsonSchemaGenerator\Printer\Registry;

class ValidatorGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly Configuration $configuration,
    ) {
    }

    public function generate(Registry $registry, Model $model): void
    {
        $value = $this->configuration->baseNamespace;
        // @fixme: Implement generate() method.
    }
}
