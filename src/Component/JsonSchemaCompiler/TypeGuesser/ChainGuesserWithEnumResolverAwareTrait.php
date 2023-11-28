<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\EnumResolver;

trait ChainGuesserWithEnumResolverAwareTrait
{
    use ChainGuesserAwareTrait {
        setChainGuesser as parentSetChainGuesser;
    }

    protected EnumResolver $enumResolver;

    public function setChainGuesser(ChainGuesser $chainGuesser, bool $clearNaming = false): void
    {
        $this->parentSetChainGuesser($chainGuesser);
        $this->enumResolver = new EnumResolver(clearNaming: $clearNaming);
    }
}
