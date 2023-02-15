<?php

namespace Jane\Component\JsonSchemaMetadata\NodeTraverser;

use Jane\Component\JsonSchemaMetadata\Metadata\Registry;

class ChainNodeTraverser implements NodeTraverserInterface
{
    public function __construct(
        /** @var array<NodeTraverserInterface> */
        private array $traversers = [],
    ) {
    }

    public function addNodeTraverser(NodeTraverserInterface $nodeTraverser): void
    {
        $this->traversers[] = $nodeTraverser;
    }

    public function traverse(array $data, string $reference): bool
    {
        foreach ($this->traversers as $traverser) {
            if ($traverser->traverse($data, $reference)) {
                return true;
            }
        }

        return false;
    }

    public static function create(Registry $registry): NodeTraverserInterface
    {
        $chainNodeTraverser = new self();
        $chainNodeTraverser->addNodeTraverser(new JsonSchemaTraverser($registry, $chainNodeTraverser));

        return $chainNodeTraverser;
    }
}
