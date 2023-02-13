<?php

namespace Jane\Component\JsonSchemaMetadata\NodeTraverser;

interface NodeTraverserInterface
{
    public function traverse(mixed $data, string $reference): bool;
}
