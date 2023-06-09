<?php

namespace Jane\Component\JsonSchemaGenerator\Printer;

class Registry
{
    /** @var File[] */
    private $files = [];

    public function addFile(File $file): void
    {
        $this->files[] = $file;
    }

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }
}
