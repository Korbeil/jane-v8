<?php

namespace Jane\Component\JsonSchemaMetadata\NodeTraverser;

use Jane\Component\JsonSchemaMetadata\Metadata\Reference;
use Jane\Component\JsonSchemaMetadata\Metadata\Registry;

class ReferenceTraverser implements NodeTraverserInterface
{
    private const SKIP_REFERENCE_TRAVERSER = 'skip_reference';

    public function __construct(
        private readonly Registry $registry,
        private readonly NodeTraverserInterface $chainNodeTraverser,
    ) {
    }

    public function traverse(array $data, string $reference, array $context = []): bool
    {
        if ($context[self::SKIP_REFERENCE_TRAVERSER] ?? false) {
            return false;
        }

        if (null !== $this->registry->currentSource() && \array_key_exists('$ref', $data)) {
            $referenceObject = new Reference($data['$ref'], $this->registry->currentSource());
            $resolvedReference = $referenceObject->resolve();
            if (!\is_array($resolvedReference)) {
                return false;
            }

            $referenceKey = sprintf('%s/reference/%s', $reference, $data['$ref']);
            $this->chainNodeTraverser->traverse($data, $reference, array_merge([self::SKIP_REFERENCE_TRAVERSER => true], $context));
            $this->chainNodeTraverser->traverse($resolvedReference, $referenceKey, $context);

            if (null === ($localSchema = $this->registry->get($reference))) {
                return false;
            }

            if (null === ($referenceSchema = $this->registry->get($referenceKey))) {
                return false;
            }

            $localSchema->merge($referenceSchema);

            return true;
        }

        return false;
    }
}
