<?php

namespace Jane\Component\JsonSchemaMetadata\NodeTraverser;

use Jane\Component\JsonSchemaMetadata\Metadata\Registry;

class DefinitionsTraverser implements NodeTraverserInterface
{
    public function __construct(
        private readonly Registry $registry,
        private readonly NodeTraverserInterface $chainNodeTraverser,
    ) {
    }

    public function traverse(array $data, string $reference, array $context = []): bool
    {
        if (\array_key_exists('definitions', $data) && \count($data['definitions']) > 0) {
            /**
             * @var string               $definitionKey
             * @var JsonSchemaDefinition $definitionSchema
             */
            foreach ($data['definitions'] as $definitionKey => $definitionSchema) {
                if ($this->registry->hasSchema(sprintf('#/definitions/%s', $definitionKey))) {
                    continue; // skip this definition, already resolved thanks to a reference
                }

                $this->chainNodeTraverser->traverse($definitionSchema, sprintf('%s/definitions/%s', $reference, $definitionKey), array_merge($context, [NodeTraverserInterface::CONTEXT_SCHEMA_NAME => $definitionKey]));
            }
        }

        if (\array_key_exists('$defs', $data) && \count($data['$defs']) > 0) {
            /**
             * @var string               $definitionKey
             * @var JsonSchemaDefinition $definitionSchema
             */
            foreach ($data['$defs'] as $definitionKey => $definitionSchema) {
                if ($this->registry->hasSchema(sprintf('#/$defs/%s', $definitionKey))) {
                    continue; // skip this definition, already resolved thanks to a reference
                }

                $this->chainNodeTraverser->traverse($definitionSchema, sprintf('%s/definitions/%s', $reference, $definitionKey), array_merge($context, [NodeTraverserInterface::CONTEXT_SCHEMA_NAME => $definitionKey]));
            }
        }

        return false;
    }
}
