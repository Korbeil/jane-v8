<?php

namespace Jane\Component\JsonSchemaGenerator\Printer;

use Jane\Component\JsonSchemaGenerator\Configuration;
use PhpParser\PrettyPrinter\Standard;
use PhpParser\PrettyPrinterAbstract;
use Symfony\Component\Filesystem\Filesystem;

class Printer
{
    private readonly PrettyPrinterAbstract $printer;

    public function __construct(
        private readonly Configuration $configuration,
    ) {
        $this->printer = new Standard();
    }

    public function output(Registry $registry): void
    {
        if ($this->configuration->cleanGenerated) {
            $fs = new Filesystem();
            $fs->remove($this->configuration->outputDirectory);
        }

        foreach ($registry->getFiles() as $file) {
            if (!file_exists(\dirname($file->filename))) {
                mkdir(\dirname($file->filename), 0755, true);
            }

            file_put_contents($file->filename, $this->printer->prettyPrintFile([$file->node]));
        }
    }
}
