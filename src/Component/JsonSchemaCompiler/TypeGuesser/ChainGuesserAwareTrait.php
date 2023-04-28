<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

trait ChainGuesserAwareTrait
{
    protected ChainGuesser $chainGuesser;

    public function setChainGuesser(ChainGuesser $chainGuesser): void
    {
        $this->chainGuesser = $chainGuesser;
    }
}
