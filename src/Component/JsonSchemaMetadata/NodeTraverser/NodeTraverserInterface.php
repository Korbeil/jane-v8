<?php

namespace Jane\Component\JsonSchemaMetadata\NodeTraverser;

interface NodeTraverserInterface
{
    public const CONTEXT_SCHEMA_NAME = 'schema';
    public const CONTEXT_SKIP_REFERENCE = 'skip_reference';

    /**
     * @param JsonSchemaDefinition $data
     * @param JsonSchemaContext    $context
     */
    public function traverse(array $data, string $reference, array $context = []): bool;
}
