<?php

namespace Jane\Component\JsonSchemaGenerator\Printer;

use Jane\Component\JsonSchemaGenerator\Configuration;
use Jane\Component\JsonSchemaGenerator\Runtime\AdditionalAndPatternProperties;
use Jane\Component\JsonSchemaGenerator\Runtime\AdditionalPropertiesInterface;
use Jane\Component\JsonSchemaGenerator\Runtime\PatternPropertiesInterface;
use PhpCsFixer\Console\Application;
use PhpCsFixer\Console\Command\FixCommand;
use PhpCsFixer\ToolInfo;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinterAbstract;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;

class Printer
{
    private Parser $parser;

    public function __construct(
        private readonly Configuration $configuration,
        private readonly PrettyPrinterAbstract $printer,
    ) {
        $this->parser = (new ParserFactory())->createForHostVersion();
    }

    public function output(Registry $registry): void
    {
        if ($this->configuration->cleanGenerated) {
            $fs = new Filesystem();
            $fs->remove($this->configuration->outputDirectory);
        }

        $this->outputRuntime($registry);

        foreach ($registry->getFiles() as $file) {
            if (!file_exists(\dirname($file->filename))) {
                mkdir(\dirname($file->filename), 0755, true);
            }

            file_put_contents($file->filename, $this->printer->prettyPrintFile([$file->node]));
        }

        if ($this->configuration->useFixer) {
            $this->fix($this->configuration->outputDirectory);
        }
    }

    private function outputRuntime(Registry $registry): void
    {
        if ($registry->needsPatternPropertiesRuntime || $registry->needsAdditionalPropertiesRuntime) {
            $trait = new \ReflectionClass(AdditionalAndPatternProperties::class);
            $this->outputRuntimeClass($registry, $trait);
        }
        if ($registry->needsPatternPropertiesRuntime) {
            $interface = new \ReflectionClass(PatternPropertiesInterface::class);
            $this->outputRuntimeClass($registry, $interface);
        }
        if ($registry->needsAdditionalPropertiesRuntime) {
            $interface = new \ReflectionClass(AdditionalPropertiesInterface::class);
            $this->outputRuntimeClass($registry, $interface);
        }
    }

    /**
     * @param \ReflectionClass<object> $class
     */
    private function outputRuntimeClass(Registry $registry, \ReflectionClass $class): void
    {
        /** @var Stmt[] $parsed */
        $parsed = $this->parser->parse((string) file_get_contents((string) $class->getFileName()));
        /** @var Namespace_ $namespaceAst */
        [$namespaceAst] = $parsed;
        $namespace = sprintf('%s\\Runtime', $this->configuration->baseNamespace);

        $stmts = new Namespace_(new Name($namespace), $namespaceAst->stmts);
        $registry->addFile(new File(sprintf('%s/Runtime/%s.php', $this->configuration->outputDirectory, $class->getShortName()), $stmts, File::TYPE_RUNTIME));
    }

    protected function getDefaultRules(): string
    {
        $rules = [
            '@Symfony' => true,
            'self_accessor' => true,
            'array_syntax' => ['syntax' => 'short'],
            'concat_space' => ['spacing' => 'one'],
            'declare_strict_types' => true,
            'header_comment' => [
                'header' => <<<EOH
This file has been auto generated by Jane,

Do not edit it directly.
EOH
                ,
            ],
        ];

        /* @phpstan-ignore-next-line */
        if (version_compare(Application::VERSION, '3.0.0', '>=')) {
            $rules['yoda_style'] = false;
        } elseif (version_compare(Application::VERSION, '2.6.0', '>=')) {
            $rules['yoda_style'] = null;
        }

        /** @var string $encodedRules */
        $encodedRules = json_encode($rules);

        return $encodedRules;
    }

    protected function fix(string $path): void
    {
        if (!class_exists(FixCommand::class)) {
            return;
        }

        $command = new FixCommand(new ToolInfo());
        $config = [
            'path' => [$path],
        ];

        if (null !== $this->configuration->fixerConfig) {
            $config['--config'] = $this->configuration->fixerConfig;
        } else {
            $config['--allow-risky'] = 'yes';
            $config['--rules'] = $this->getDefaultRules();
        }

        $command->run(new ArrayInput($config, $command->getDefinition()), new NullOutput());
    }
}
