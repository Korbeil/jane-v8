<?php

namespace Jane\Component\OpenApiParser\Exception;

abstract class FileException extends \RuntimeException
{
    public function __construct(
        string $message,
        int $code = 0,
        \Throwable $previous = null,
        private readonly ?string $path = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getPath(): ?string
    {
        return $this->path;
    }
}
