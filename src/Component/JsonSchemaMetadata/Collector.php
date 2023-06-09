<?php

namespace Jane\Component\JsonSchemaMetadata;

use Jane\Component\JsonSchemaMetadata\Exception\CannotReadFileException;
use Jane\Component\JsonSchemaMetadata\Exception\FileNotFoundException;
use Jane\Component\JsonSchemaMetadata\Metadata\Registry;
use Jane\Component\JsonSchemaMetadata\NodeTraverser\ChainNodeTraverser;
use Jane\Component\JsonSchemaMetadata\NodeTraverser\NodeTraverserInterface;
use Jane\Component\JsonSchemaParser\Parser;
use Jane\Component\JsonSchemaParser\ParserInterface;
use Symfony\Component\Filesystem\Filesystem;

class Collector implements CollectorInterface
{
    private ParserInterface $parser;
    private ?Registry $localRegistry = null;

    public function __construct(ParserInterface $parser = null)
    {
        $this->parser = $parser ?? new Parser();
    }

    /**
     * @param JsonSchemaDefinition $data
     */
    public function fromParsed(mixed $data, string $rootSchema = null, array $context = []): Registry
    {
        $registry = $this->getRegistry();

        $chainNodeTraverser = ChainNodeTraverser::create($registry);
        $chainNodeTraverser->traverse($data, Registry::ROOT_ELEMENT, [NodeTraverserInterface::CONTEXT_SCHEMA_NAME => $rootSchema]);

        return $registry;
    }

    public function fromPath(string $path, string $rootSchema = null, array $context = []): Registry
    {
        $registry = $this->getRegistry();
        $registry->addSource($path, $this->getFileContents($path));
        $registry->currentSource($path);

        /** @var JsonSchemaDefinition $parsed */
        $parsed = $this->parser->fromPath($path);

        return $this->fromParsed($parsed, $rootSchema, $context);
    }

    private function getRegistry(): Registry
    {
        if (null === $this->localRegistry) {
            $this->localRegistry = new Registry();
        }

        return $this->localRegistry;
    }

    private function getFileContents(string $path): string
    {
        $filesystem = new Filesystem();
        if (!$filesystem->exists($path)) {
            throw new FileNotFoundException(sprintf('File "%s" not found.', $path), path: $path);
        }

        $fileContents = @file_get_contents($path);

        if (false === $fileContents) {
            throw new CannotReadFileException(sprintf('Cannot read file "%s".', $path), path: $path);
        }

        return $fileContents;
    }
}
