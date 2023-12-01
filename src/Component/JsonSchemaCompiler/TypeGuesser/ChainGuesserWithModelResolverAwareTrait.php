<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\ModelResolver;

trait ChainGuesserWithModelResolverAwareTrait
{
    use ChainGuesserAwareTrait {
        setChainGuesser as parentSetChainGuesser;
    }

    protected ModelResolver $modelResolver;

    public function setChainGuesser(ChainGuesser $chainGuesser, bool $clearNaming = false): void
    {
        $this->parentSetChainGuesser($chainGuesser);
        $this->modelResolver = new ModelResolver(typeGuesser: $chainGuesser, clearNaming: $clearNaming);
    }
}
