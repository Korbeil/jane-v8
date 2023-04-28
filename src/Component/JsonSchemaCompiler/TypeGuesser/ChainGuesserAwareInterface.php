<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

interface ChainGuesserAwareInterface
{
    public function setChainGuesser(ChainGuesser $chainGuesser): void;
}
