<?php

namespace Jane\Component\JsonSchemaGenerator;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiler;
use Jane\Component\JsonSchemaGenerator\Generator\JaneNormalizersGenerator;
use Jane\Component\JsonSchemaGenerator\Generator\ModelGenerator;
use Jane\Component\JsonSchemaGenerator\Generator\NormalizerGenerator;
use Jane\Component\JsonSchemaGenerator\Generator\ValidatorGenerator;
use Jane\Component\JsonSchemaGenerator\Printer\Printer;
use Jane\Component\JsonSchemaGenerator\Printer\Registry as GeneratorRegistry;

class Generator implements GeneratorInterface
{
    private readonly ModelGenerator $modelGenerator;
    private readonly JaneNormalizersGenerator $janeNormalizersGenerator;
    private readonly NormalizerGenerator $normalizerGenerator;
    private readonly ValidatorGenerator $validatorGenerator;

    public function __construct(
        private readonly Configuration $configuration,
    ) {
        $this->modelGenerator = new ModelGenerator($this->configuration);
        $this->janeNormalizersGenerator = new JaneNormalizersGenerator($this->configuration);
        $this->normalizerGenerator = new NormalizerGenerator($this->configuration);
        $this->validatorGenerator = new ValidatorGenerator($this->configuration);
    }

    public function fromPath(string $path, string $rootModel = null): void
    {
        $compiler = new Compiler(configuration: $this->configuration);
        $this->fromRegistry($compiler->fromPath($path, $rootModel));
    }

    public function fromRegistry(Registry $registry): void
    {
        $generatorRegistry = new GeneratorRegistry();

        foreach ($registry->getModels() as $model) {
            $this->modelGenerator->generate($generatorRegistry, $model);

            if ($this->configuration->validation) {
                $this->validatorGenerator->generate($generatorRegistry, $model);
            }

            $this->normalizerGenerator->generate($generatorRegistry, $model);
        }

        $this->janeNormalizersGenerator->generate($generatorRegistry, $registry->getModels());

        $printer = new Printer($this->configuration);
        $printer->output($generatorRegistry);
    }
}
