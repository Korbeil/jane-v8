<?php

namespace Jane\Component\JsonSchemaMetadata\NodeTraverser;

use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Reference;
use Jane\Component\JsonSchemaMetadata\Metadata\Registry;

class ReferenceTraverser implements NodeTraverserInterface
{
    public function __construct(
        private readonly Registry $registry,
        private readonly NodeTraverserInterface $chainNodeTraverser,
    ) {
    }

    public function traverse(array $data, string $reference, array $context = []): bool
    {
        if ($context[NodeTraverserInterface::CONTEXT_SKIP_REFERENCE] ?? false) {
            return false;
        }

        if (null !== $this->registry->currentSource() && \array_key_exists('$ref', $data)) {
            $referenceObject = new Reference($data['$ref'], $this->registry->currentSource());
            $resolvedReference = $referenceObject->resolve();
            if (!\is_array($resolvedReference)) {
                return false;
            }

            $referenceKey = sprintf('#/references/%s', $data['$ref']);
            $this->chainNodeTraverser->traverse($data, $reference, array_merge([NodeTraverserInterface::CONTEXT_SKIP_REFERENCE => true], $context));

            if (null === ($referenceSchema = $this->registry->get($referenceKey))) {
                $this->chainNodeTraverser->traverse($resolvedReference, $referenceKey, $context);

                /** @var JsonSchema|null $referenceSchema */
                $referenceSchema = $this->registry->get($referenceKey);
                if (null === $referenceSchema) {
                    return false;
                }
            }

            if (null === ($localSchema = $this->registry->get($reference))) {
                return false;
            }

            $localSchema->merge($referenceSchema);

            return true;
        }

        return false;
    }
}
