<?php

namespace Jane\Component\JsonSchemaMetadata\NodeTraverser;

use Jane\Component\JsonSchemaMetadata\Metadata\Registry;
use Jane\Component\JsonSchemaMetadata\Naming\Naming;

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

    public function traverse(array $data, string $reference, array $context = []): bool
    {
        foreach ($this->traversers as $traverser) {
            if ($traverser->traverse($data, $reference, $context)) {
                return true;
            }
        }

        return false;
    }

    public static function create(Registry $registry, Naming $naming): NodeTraverserInterface
    {
        $chainNodeTraverser = new self();
        $chainNodeTraverser->addNodeTraverser(new DefinitionsTraverser($chainNodeTraverser));
        $chainNodeTraverser->addNodeTraverser(new ReferenceTraverser($registry, $chainNodeTraverser));
        $chainNodeTraverser->addNodeTraverser(new JsonSchemaTraverser($registry, $chainNodeTraverser, $naming));

        return $chainNodeTraverser;
    }
}
