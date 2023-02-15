<?php

namespace Jane\Component\JsonSchemaMetadata;

use Jane\Component\JsonSchemaMetadata\Metadata\Registry;
use Jane\Component\JsonSchemaMetadata\NodeTraverser\ChainNodeTraverser;
use Jane\Component\JsonSchemaParser\Parser;
use Jane\Component\JsonSchemaParser\ParserInterface;

class Collector implements CollectorInterface
{
    private ParserInterface $parser;

    public function __construct(ParserInterface $parser = null)
    {
        $this->parser = $parser ?? new Parser();
    }

    /**
     * @param JsonSchemaDefinition $data
     */
    public function collect(mixed $data): Registry
    {
        $registry = new Registry();

        $chainNodeTraverser = ChainNodeTraverser::create($registry);
        $chainNodeTraverser->traverse($data, '#');

        return $registry;
    }

    public function fromPath(string $path): Registry
    {
        /** @var JsonSchemaDefinition $parsed */
        $parsed = $this->parser->parse($path);

        return $this->collect($parsed);
    }
}
