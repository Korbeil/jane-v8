<?php

namespace Jane\Component\JsonSchemaMetadata;

use Jane\Component\JsonSchemaMetadata\Metadata\Registry;
use Jane\Component\JsonSchemaMetadata\NodeTraverser\ChainNodeTraverser;
use Jane\Component\JsonSchemaParser\Parser;
use Jane\Component\JsonSchemaParser\ParserInterface;

class Compiler implements CompilerInterface
{
    private readonly ParserInterface $parser;

    public function __construct(ParserInterface $parser = null)
    {
        $this->parser = $parser ?? new Parser();
    }

    public function compile(string $path): Registry
    {
        $registry = new Registry();

        $parsed = $this->parser->parse($path);

        $chainNodeTraverser = ChainNodeTraverser::create($registry);
        $chainNodeTraverser->traverse($parsed, '#');

        return $registry;
    }
}
