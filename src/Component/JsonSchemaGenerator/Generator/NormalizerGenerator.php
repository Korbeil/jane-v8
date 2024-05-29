<?php

namespace Jane\Component\JsonSchemaGenerator\Generator;

use AutoMapper\AutoMapper;
use AutoMapper\AutoMapperInterface;
use Jane\Component\JsonSchemaCompiler\Compiled\Model;
use Jane\Component\JsonSchemaGenerator\Configuration;
use Jane\Component\JsonSchemaGenerator\Printer\File;
use Jane\Component\JsonSchemaGenerator\Printer\Registry;
use PhpParser\BuilderFactory;
use PhpParser\BuilderHelpers;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class NormalizerGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly Configuration $configuration,
    ) {
    }

    public function generate(Registry $registry, Model $model): void
    {
        $factory = new BuilderFactory();

        $class = $factory
            ->class($model->normalizerName)
            ->implement('NormalizerInterface')
            ->implement('DenormalizerInterface')
            ->addStmt($factory->property('autoMapper')->setType('AutoMapperInterface')->makePrivate()->makeReadonly())
            ->addStmt(
                $factory
                    ->method('__construct')
                    ->addParam($factory->param('autoMapper')->setType('AutoMapperInterface')->setDefault(null))
                    ->addStmts([
                        new Expression(new Assign(
                            $factory->propertyFetch(new Variable('this'), 'autoMapper'),
                            new Coalesce(
                                new Variable('autoMapper'),
                                $factory->staticCall('AutoMapper', 'create'),
                            ),
                        )),
                    ])
            )
            ->addStmt(
                $factory
                    ->method('normalize')
                    ->setDocComment(sprintf(<<<DOC
/**
 * @param %s \$object
 *
 * @return array
 */
DOC, $model->modelName))
                    ->addParam($factory->param('object')->setType('mixed'))
                    ->addParam($factory->param('format')->setType('string')->setDefault(null))
                    ->addParam($factory->param('context')->setType('array')->setDefault([]))
                    ->setReturnType('array|string|int|float|bool|\ArrayObject|null')
                    ->addStmts($this->mapStatements($model->modelName))
            )
            ->addStmt(
                $factory
                    ->method('supportsNormalization')
                    ->addParam($factory->param('data')->setType('mixed'))
                    ->addParam($factory->param('format')->setType('string')->setDefault(null))
                    ->addParam($factory->param('context')->setType('array')->setDefault([]))
                    ->setReturnType('bool')
                    ->addStmts($this->supportNormalizationStatements($model))
            )
            ->addStmt(
                $factory
                    ->method('denormalize')
                    ->setDocComment(sprintf(<<<DOC
/**
 * @param array|object \$data
 *
 * @return %s
 */
DOC, $model->modelName))
                    ->addParam($factory->param('data')->setType('mixed'))
                    ->addParam($factory->param('type')->setType('string'))
                    ->addParam($factory->param('format')->setType('string')->setDefault(null))
                    ->addParam($factory->param('context')->setType('array')->setDefault([]))
                    ->setReturnType('mixed')
                    ->addStmts($this->mapStatements($model->modelName, false))
            )
            ->addStmt(
                $factory
                    ->method('supportsDenormalization')
                    ->addParam($factory->param('data')->setType('mixed'))
                    ->addParam($factory->param('type')->setType('string'))
                    ->addParam($factory->param('format')->setType('string')->setDefault(null))
                    ->addParam($factory->param('context')->setType('array')->setDefault([]))
                    ->setReturnType('bool')
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
            ->addStmt($factory->use(AutoMapperInterface::class))
            ->addStmt($factory->use(AutoMapper::class))
            ->addStmt($factory->use(NormalizerInterface::class))
            ->addStmt($factory->use(DenormalizerInterface::class))
            ->addStmt($class)
        ;

        $registry->addFile(new File(sprintf('%s/Normalizer/%s.php', $this->configuration->outputDirectory, $model->normalizerName), $node->getNode(), File::TYPE_NORMALIZER));
    }

    /**
     * @return Stmt[]
     */
    private function mapStatements(string $class, bool $normalization = true): array
    {
        $factory = new BuilderFactory();

        $stmts = [];
        if (!$normalization) {
            $stmts[] = new Expression(new Assign(new Variable('class'), new Variable('type')), ['comments' => [BuilderHelpers::normalizeDocComment('/** @var class-string $class */')]]);
        }

        $stmts[] = new Expression(new Assign(
            new Variable('output'),
            $factory->methodCall(
                $factory->propertyFetch(new Variable('this'), 'autoMapper'),
                'map',
                [
                    new Arg($normalization ? new Variable('object') : new Variable('data')),
                    new Arg($normalization ? new String_('array') : new Variable('class')),
                    new Arg(new Variable('context')),
                ],
            ),
        ), ['comments' => [BuilderHelpers::normalizeDocComment(sprintf('/** @var %s $output */', $normalization ? 'array' : $class))]]);
        $stmts[] = new Return_(new Variable('output'));

        return $stmts;
    }

    /**
     * @return Stmt[]
     */
    private function supportNormalizationStatements(Model $model): array
    {
        $factory = new BuilderFactory();

        return [new Return_(new Instanceof_($factory->var('data'), new Name($model->name)))];
    }

    /**
     * @return Stmt[]
     */
    private function supportDenormalizationStatements(Model $model): array
    {
        $factory = new BuilderFactory();

        return [new Return_(new Identical($factory->var('type'), $factory->classConstFetch($model->name, 'class')))];
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
