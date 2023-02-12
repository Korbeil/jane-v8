<?php

namespace Jane\Component\OpenApiParser\Exception;

final class UnsupportedFileFormatException extends FileException
{
    public function __construct(
        string $message,
        int $code = 0,
        \Throwable $previous = null,
        string $path = null,
        private readonly ?string $fileExtension = null,
    ) {
        parent::__construct($message, $code, $previous, $path);
    }

    public function getFileExtension(): ?string
    {
        return $this->fileExtension;
    }
}
