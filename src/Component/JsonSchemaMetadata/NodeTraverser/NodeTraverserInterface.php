<?php

namespace Jane\Component\JsonSchemaMetadata\NodeTraverser;

interface NodeTraverserInterface
{
    /**
     * @param JsonSchemaDefinition $data
     */
    public function traverse(array $data, string $reference): bool;
}
