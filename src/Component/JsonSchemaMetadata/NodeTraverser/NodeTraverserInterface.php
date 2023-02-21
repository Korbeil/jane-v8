<?php

namespace Jane\Component\JsonSchemaMetadata\NodeTraverser;

interface NodeTraverserInterface
{
    /**
     * @param JsonSchemaDefinition            $data
     * @param array{skip_reference?: boolean} $context
     */
    public function traverse(array $data, string $reference, array $context = []): bool;
}
