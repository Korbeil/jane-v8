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
            [$referenceObject, $resolvedReference] = $this->resolveReference($data);
            if (null === $referenceObject || false === $resolvedReference) {
                return false;
            }

            // build reference Model name
            $definitionName = $referenceObject->getReferenceUri()->getFragment();
            if (null === $definitionName) {
                return false; // cannot resolve a definition with no fragment name
            }

            $definitionName = ltrim($definitionName, '/');
            $definitionParts = explode('/', $definitionName);
            array_shift($definitionParts);
            $definitionModelName = '';
            foreach ($definitionParts as $definitionPart) {
                $definitionModelName .= ucfirst($definitionPart);
            }

            if (!$this->registry->hasSchema($data['$ref'])) {
                $this->chainNodeTraverser->traverse($resolvedReference, $data['$ref'], array_merge($context, [NodeTraverserInterface::CONTEXT_SCHEMA_NAME => $definitionModelName, NodeTraverserInterface::CONTEXT_SKIP_REFERENCE => true]));
            }

            if ($this->registry->hasSchema($data['$ref'])) {
                /** @var JsonSchema|null $referenceSchema */
                $referenceSchema = $this->registry->get($data['$ref']);

                if (null === $referenceSchema) {
                    return false;
                }

                if ($reference !== $data['$ref']) {
                    $this->registry->addSchema($reference, $referenceSchema);
                }
            } else {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * @param JsonSchemaDefinition $data
     *
     * @return array{0: Reference|null, 1: false|JsonSchemaDefinition}
     */
    private function resolveReference(array $data): array
    {
        $copiedData = $data;
        unset($copiedData['$ref']);

        if (null === $this->registry->currentSource() || !\array_key_exists('$ref', $data)) {
            return [null, false];
        }

        $referenceObject = new Reference($data['$ref'], $this->registry->currentSource());
        /** @var string|JsonSchemaDefinition $resolvedReference */
        $resolvedReference = $referenceObject->resolve();

        if (!\is_array($resolvedReference)) {
            return [null, false];
        }

        $resolvedReference = array_merge($resolvedReference, $copiedData);
        if (\array_key_exists('$ref', $resolvedReference)) {
            return $this->resolveReference($resolvedReference);
        }

        return [$referenceObject, $resolvedReference];
    }
}
