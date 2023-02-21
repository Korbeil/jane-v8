<?php

namespace Jane\Component\JsonSchemaMetadata\NodeTraverser;

class DefinitionsTraverser implements NodeTraverserInterface
{
    public function __construct(
        private readonly NodeTraverserInterface $chainNodeTraverser,
    ) {
    }

    public function traverse(array $data, string $reference, array $context = []): bool
    {
        if (\array_key_exists('$defs', $data)) {
            /**
             * @var JsonSchemaDefinition $definitionSchema
             */
            foreach ($data['$defs'] as $definitionKey => $definitionSchema) {
                $this->chainNodeTraverser->traverse($definitionSchema, sprintf('%s/definitions/%s', $reference, $definitionKey), $context);
            }
        }

        return false;
    }
}
