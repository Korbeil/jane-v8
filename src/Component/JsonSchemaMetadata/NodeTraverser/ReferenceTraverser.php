<?php

namespace Jane\Component\JsonSchemaMetadata\NodeTraverser;

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

        if (\array_key_exists('$ref', $data)) {
            $localReference = $data['$ref'];
            if (str_contains($data['$ref'], '$defs')) {
                $localReference = str_replace('$defs', 'definitions', $localReference);
            }

            if (null === ($localSchema = $this->registry->get($localReference))) {
                return false;
            }

            $this->chainNodeTraverser->traverse($data, $reference, array_merge([self::SKIP_REFERENCE_TRAVERSER => true], $context));

            if (null === ($referenceSchema = $this->registry->get($reference))) {
                return false;
            }

            $referenceSchema->merge($localSchema);

            return true;
        }

        return false;
    }
}
