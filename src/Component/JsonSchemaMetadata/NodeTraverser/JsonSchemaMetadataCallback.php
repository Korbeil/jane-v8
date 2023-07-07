<?php

namespace Jane\Component\JsonSchemaMetadata\NodeTraverser;

interface JsonSchemaMetadataCallback
{
    /**
     * @param JsonSchemaDefinition $data
     *
     * @return JsonSchemaDefinition
     */
    public function process(array $data): array;
}
