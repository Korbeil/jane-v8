<?php

namespace Jane\Component\JsonSchemaCompiler;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry as CompiledRegistry;
use Jane\Component\JsonSchemaMetadata\Metadata\Registry as MetadataRegistry;

interface CompilerInterface
{
    public function fromPath(string $path, string $rootModel = null): CompiledRegistry;

    public function fromMetadata(MetadataRegistry $sourceRegistry, string $rootModel = null): CompiledRegistry;
}
