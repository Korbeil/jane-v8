<?php

namespace Jane\Component\JsonSchemaGenerator\Generator;

use Jane\Component\JsonSchemaCompiler\Compiled\Model;
use Jane\Component\JsonSchemaGenerator\Configuration;
use Jane\Component\JsonSchemaGenerator\Generator\Normalizer\DenormalizerTrait;
use Jane\Component\JsonSchemaGenerator\Generator\Normalizer\NormalizerTrait;
use Jane\Component\JsonSchemaGenerator\Printer\File;
use Jane\Component\JsonSchemaGenerator\Printer\Registry;
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Return_;

class NormalizerGenerator implements GeneratorInterface
{
    use DenormalizerTrait;
    use NormalizerTrait;

    public function __construct(
        private readonly Configuration $configuration,
    ) {
    }

    public function generate(Registry $registry, Model $model): void
    {
        $factory = new BuilderFactory();

        $class = $factory
            ->class($normalizerName = sprintf('%sNormalizer', ucfirst($model->name))) // @fixme normalizer name
            ->addStmt(
                $factory
                    ->method('normalize')
                    ->setDocComment(sprintf('/** @param %s $object */', ucfirst($model->name))) // @fixme
                    ->addParam($factory->param('object')->setType('mixed'))
                    ->addParam($factory->param('format')->setType('string')->setDefault(null))
                    ->addParam($factory->param('context')->setType('array')->setDefault([]))
                    ->addStmts($this->normalizeStatements($model))
            )
            ->addStmt(
                $factory
                    ->method('supportsNormalization')
                    ->setDocComment('/** @return bool */')
                    ->addParam($factory->param('data')->setType('mixed'))
                    ->addParam($factory->param('format')->setType('string')->setDefault(null))
                    ->addParam($factory->param('context')->setType('array')->setDefault([]))
                    ->addStmts($this->supportNormalizationStatements($model))
            )
            ->addStmt(
                $factory
                    ->method('denormalize')
                    ->setDocComment(sprintf('/** @return %s */', ucfirst($model->name))) // @fixme
                    ->addParam($factory->param('data')->setType('mixed'))
                    ->addParam($factory->param('type')->setType('string'))
                    ->addParam($factory->param('format')->setType('string')->setDefault(null))
                    ->addParam($factory->param('context')->setType('array')->setDefault([]))
                    ->addStmts($this->denormalizeStatements($model))
            )
            ->addStmt(
                $factory
                    ->method('supportsDenormalization')
                    ->setDocComment('/** @return bool */')
                    ->addParam($factory->param('data')->setType('mixed'))
                    ->addParam($factory->param('type')->setType('string'))
                    ->addParam($factory->param('format')->setType('string')->setDefault(null))
                    ->addParam($factory->param('context')->setType('array')->setDefault([]))
                    ->addStmts($this->supportDenormalizationStatements($model))
            )
            ->addStmt(
                $factory
                    ->method('getSupportedTypes')
                    ->setDocComment('/** @return array<class-string, bool> */')
                    ->addParam($factory->param('format')->setType('?string'))
                    ->setReturnType('array')
                    ->addStmts($this->supportedTypesStatements($model))
            )
        ;

        $node = $factory
            ->namespace(sprintf('%s\\Normalizer', $this->configuration->baseNamespace))
            ->addStmt($factory->use(sprintf('%s\\Model\\%s', $this->configuration->baseNamespace, $model->name)))
            ->addStmt($class)
        ;

        $registry->addFile(new File(sprintf('%s/Normalizer/%s.php', $this->configuration->outputDirectory, $normalizerName), $node->getNode(), File::TYPE_NORMALIZER));
    }

    /**
     * @return Stmt[]
     */
    private function supportedTypesStatements(Model $model): array
    {
        $factory = new BuilderFactory();

        return [new Return_(new Array_([new ArrayItem($factory->constFetch('true'), $factory->classConstFetch($model->name, 'class'))]))];
    }
}
