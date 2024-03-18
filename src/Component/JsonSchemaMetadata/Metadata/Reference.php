<?php

declare(strict_types=1);

namespace Jane\Component\JsonSchemaMetadata\Metadata;

use League\Uri\Contracts\UriInterface;
use League\Uri\Uri;
use League\Uri\UriString;
use Rs\Json\Pointer;
use Symfony\Component\Yaml\Yaml;

class Reference
{
    /** @var array<string, string> */
    private static array $fileCache = [];
    /** @var array<string, Pointer> */
    private static array $pointerCache = [];
    /** @var array<string, JsonSchemaDefinition> */
    private static array $arrayCache = [];

    /** @var JsonSchemaDefinition|null */
    private array|string|null $resolved = null;
    private UriInterface $referenceUri;
    private UriInterface $originUri;
    private UriInterface $mergedUri;

    public function __construct(string $reference, string $origin)
    {
        $reference = $this->fixPath($reference);
        $origin = $this->fixPath($origin);
        $originParts = UriString::parse($origin);
        $referenceParts = parse_url($reference);
        if (false === $referenceParts) {
            throw new \RuntimeException(); // @fixme
        }

        $mergedParts = array_merge($originParts, $referenceParts);

        if (\array_key_exists('path', $referenceParts)) {
            $mergedParts['path'] = $this->joinPath(\dirname($originParts['path']), $referenceParts['path']);
        }

        $this->referenceUri = Uri::createFromString($reference);
        $this->originUri = Uri::createFromString($origin);
        $this->mergedUri = Uri::createFromComponents($mergedParts);
    }

    /**
     * Resolve a JSON Reference.
     *
     * @return string|JsonSchemaDefinition Return the JSON value referenced (as an array)
     */
    public function resolve(): string|array
    {
        if (null === $this->resolved) {
            $this->resolved = $this->doResolve();
        }

        return $this->resolved;
    }

    /**
     * Resolve a JSON Reference for a Schema.
     *
     * @return string|JsonSchemaDefinition Return the json value referenced
     */
    protected function doResolve(): string|array
    {
        $fragment = (string) $this->mergedUri->withFragment(null);
        $reference = sprintf('%s_%s', $fragment, $this->mergedUri->getFragment());

        // load file contents info self::$fileCache
        if (!\array_key_exists($fragment, self::$fileCache)) {
            $contents = file_get_contents($fragment);
            if (false === $contents) {
                throw new \RuntimeException(); // @fixme
            }

            $encoded = json_decode($contents, true);

            if (null === $encoded || false === $encoded || \JSON_ERROR_NONE !== json_last_error()) {
                $decoded = Yaml::parse($contents, Yaml::PARSE_OBJECT | Yaml::PARSE_OBJECT_FOR_MAP | Yaml::PARSE_DATETIME | Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE);
                /** @var string $contents */
                $contents = json_encode($decoded);
            }

            self::$fileCache[$fragment] = $contents;
        }

        if (!\array_key_exists($reference, self::$arrayCache)) {
            if ('' === $this->mergedUri->getFragment()) {
                /** @var JsonSchemaDefinition $array */
                $array = json_decode(self::$fileCache[$fragment], true);
            } else {
                if (!\array_key_exists($fragment, self::$pointerCache)) {
                    self::$pointerCache[$fragment] = new Pointer(self::$fileCache[$fragment]);
                }

                /** @var string $contents */
                $contents = self::$pointerCache[$fragment]->get($this->mergedUri->getFragment() ?? '');
                /** @var string $encodedContents */
                $encodedContents = json_encode($contents);
                /** @var JsonSchemaDefinition $array */
                $array = json_decode($encodedContents, true);
            }

            self::$arrayCache[$reference] = $array;
        }

        return self::$arrayCache[$reference];
    }

    /**
     * Return true if reference and origin are in the same document.
     */
    public function isInCurrentDocument(): bool
    {
        return
            $this->mergedUri->getScheme() === $this->originUri->getScheme()
            && $this->mergedUri->getHost() === $this->originUri->getHost()
            && $this->mergedUri->getPort() === $this->originUri->getPort()
            && $this->mergedUri->getPath() === $this->originUri->getPath()
            && $this->mergedUri->getQuery() === $this->originUri->getQuery()
        ;
    }

    public function getMergedUri(): UriInterface
    {
        return $this->mergedUri;
    }

    public function getReferenceUri(): UriInterface
    {
        return $this->referenceUri;
    }

    public function getOriginUri(): UriInterface
    {
        return $this->originUri;
    }

    /**
     * Join path like unix path join :.
     *
     *   a/b + c => a/b/c
     *   a/b + /c => /c
     *   a/b/c + .././d => a/b/d
     */
    private function joinPath(string ...$paths): string
    {
        /** @var string|null $resultPath */
        $resultPath = null;

        foreach ($paths as $path) {
            if (null === $resultPath || (mb_strlen($path) > 0 && '/' === $path[0])) {
                $resultPath = $path;
            } else {
                $resultPath .= sprintf('/%s', $path);
            }
        }

        if (null === $resultPath) {
            throw new \RuntimeException(); // @fixme
        }

        /** @var string $resultPath */
        $resultPath = preg_replace('~/{2,}~', '/', $resultPath);

        if ('/' === $resultPath) {
            return '/';
        }

        $resultPathParts = [];
        foreach (explode('/', rtrim($resultPath, '/')) as $part) {
            if ('.' === $part) {
                continue;
            }

            if ('..' === $part && \count($resultPathParts) > 0) {
                array_pop($resultPathParts);
                continue;
            }

            $resultPathParts[] = $part;
        }

        return implode('/', $resultPathParts);
    }

    private function fixPath(string $path): string
    {
        if ('\\' === \DIRECTORY_SEPARATOR) {
            $path = lcfirst(str_replace(\DIRECTORY_SEPARATOR, '/', $path));
        }

        return $path;
    }
}
