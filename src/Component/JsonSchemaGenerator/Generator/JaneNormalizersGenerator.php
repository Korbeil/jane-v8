<?php

namespace Jane\Component\JsonSchemaGenerator\Generator;

use AutoMapper\AutoMapper;
use Jane\Component\JsonSchemaCompiler\Compiled\Model;
use Jane\Component\JsonSchemaGenerator\Configuration;
use Jane\Component\JsonSchemaGenerator\Printer\File;
use Jane\Component\JsonSchemaGenerator\Printer\Registry;
use PhpParser\BuilderFactory;
use PhpParser\BuilderHelpers;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class JaneNormalizersGenerator
{
    public function __construct(
        private readonly Configuration $configuration,
    ) {
    }

    /**
     * @param Model[] $models
     */
    public function generate(Registry $registry, array $models): void
    {
        $factory = new BuilderFactory();

        $modelsConst = [];
        $normalizersConst = [];
        foreach ($models as $model) {
            $modelsConst[] = new ArrayItem($factory->constFetch('false'), new ClassConstFetch(new Name($model->name), 'class'));
            $normalizersConst[] = new ArrayItem(new ClassConstFetch(new Name($model->normalizerName), 'class'), new ClassConstFetch(new Name($model->name), 'class'));
        }

        $class = $factory
            ->class('JaneNormalizers')
            ->implement('NormalizerInterface')
            ->implement('DenormalizerInterface')
            ->addStmt($factory->property('autoMapper')->setType('?AutoMapper')->makePrivate()->makeReadonly())
            ->addStmt($factory->property('normalizersCache')->setType('array')->setDefault([])->setDocComment('/** @var (NormalizerInterface&DenormalizerInterface)[] */'))
            ->addStmt($factory->classConst('MODELS', $modelsConst))
            ->addStmt($factory->classConst('NORMALIZERS', $normalizersConst))
            ->addStmt(
                $factory
                    ->method('__construct')
                    ->addParam($factory->param('autoMapper')->setType('AutoMapper')->setDefault(null))
                    ->addStmts([
                        new Expression(new Assign(
                            $factory->propertyFetch(new Variable('this'), 'autoMapper'),
                            new Variable('autoMapper'),
                        )),
                    ])
            )
            ->addStmt(
                $factory
                    ->method('normalize')
                    ->setDocComment(<<<DOC
 /**
 * @param object \$object
 *
 * @return array
 */
 DOC)
                    ->addParam($factory->param('object')->setType('mixed'))
                    ->addParam($factory->param('format')->setType('string')->setDefault(null))
                    ->addParam($factory->param('context')->setType('array')->setDefault([]))
                    ->setReturnType('array|string|int|float|bool|\ArrayObject|null')
                    ->addStmts([new Return_(new MethodCall(new MethodCall(new Variable('this'), 'getNormalizer', [new Arg($factory->classConstFetch(new Variable('object'), 'class'))]), 'normalize', [
                        new Arg(new Variable('object')), new Arg(new Variable('format')), new Arg(new Variable('context')),
                    ]))])
            )
            ->addStmt(
                $factory
                    ->method('supportsNormalization')
                    ->setDocComment(<<<DOC
 /**
 * @param object \$data
 *
 * @return bool
 */
 DOC)
                    ->addParam($factory->param('data')->setType('mixed'))
                    ->addParam($factory->param('format')->setType('string')->setDefault(null))
                    ->addParam($factory->param('context')->setType('array')->setDefault([]))
                    ->setReturnType('bool')
                    ->addStmts($this->supportNormalizationStatements())
            )
            ->addStmt(
                $factory
                    ->method('denormalize')
                    ->setDocComment(<<<DOC
 /**
 * @param array|object \$data
 *
 * @return object
 */
 DOC)
                    ->addParam($factory->param('data')->setType('mixed'))
                    ->addParam($factory->param('type')->setType('string'))
                    ->addParam($factory->param('format')->setType('string')->setDefault(null))
                    ->addParam($factory->param('context')->setType('array')->setDefault([]))
                    ->setReturnType('mixed')
                    ->addStmts([new Return_(new MethodCall(new MethodCall(new Variable('this'), 'getNormalizer', [new Arg(new Variable('type'))]), 'denormalize', [
                        new Arg(new Variable('data')), new Arg(new Variable('type')), new Arg(new Variable('format')), new Arg(new Variable('context')),
                    ]))])
            )
            ->addStmt(
                $factory
                    ->method('supportsDenormalization')
                    ->setDocComment('/** @return bool */')
                    ->addParam($factory->param('data')->setType('mixed'))
                    ->addParam($factory->param('type')->setType('string'))
                    ->addParam($factory->param('format')->setType('string')->setDefault(null))
                    ->addParam($factory->param('context')->setType('array')->setDefault([]))
                    ->setReturnType('bool')
                    ->addStmts($this->supportDenormalizationStatements())
            )
            ->addStmt(
                $factory
                    ->method('getSupportedTypes')
                    ->setDocComment('/** @return array<class-string, bool> */')
                    ->addParam($factory->param('format')->setType('?string'))
                    ->setReturnType('array')
                    ->addStmts([new Return_($factory->classConstFetch('static', 'MODELS'))])
            )
            ->addStmt(
                $factory
                    ->method('getNormalizer')
                    ->addParam($factory->param('normalizer')->setType('string'))
                    ->setReturnType('NormalizerInterface&DenormalizerInterface')
                    ->addStmts($this->getNormalizerStatements())
            )
        ;

        $node = $factory
            ->namespace(sprintf('%s\\Normalizer', $this->configuration->baseNamespace))
            ->addStmt($factory->use(AutoMapper::class))
            ->addStmt($factory->use(NormalizerInterface::class))
            ->addStmt($factory->use(DenormalizerInterface::class))
        ;

        foreach ($models as $model) {
            $node->addStmt($factory->use(sprintf('%s\\Model\\%s', $this->configuration->baseNamespace, $model->name)));
        }

        $node->addStmt($class);

        $registry->addFile(new File(sprintf('%s/Normalizer/JaneNormalizers.php', $this->configuration->outputDirectory), $node->getNode(), File::TYPE_NORMALIZER));
    }

    /**
     * @return Stmt[]
     */
    private function supportNormalizationStatements(): array
    {
        $factory = new BuilderFactory();

        return [
            new Return_(new FuncCall(new Name('\in_array'), [
                new Arg($factory->classConstFetch(new Variable('data'), 'class')),
                new Arg(new FuncCall(new Name('array_keys'), [new Arg($factory->classConstFetch('static', 'MODELS'))])),
                new Arg($factory->constFetch('true')),
            ])),
        ];
    }

    /**
     * @return Stmt[]
     */
    private function supportDenormalizationStatements(): array
    {
        $factory = new BuilderFactory();

        return [
            new Return_(new FuncCall(new Name('\in_array'), [
                new Arg(new Variable('type')),
                new Arg(new FuncCall(new Name('array_keys'), [new Arg($factory->classConstFetch('static', 'MODELS'))])),
                new Arg($factory->constFetch('true')),
            ])),
        ];
    }

    /**
     * @return Stmt[]
     */
    private function getNormalizerStatements(): array
    {
        $factory = new BuilderFactory();

        return [
            new If_(
                new BooleanNot(new FuncCall(new Name('\array_key_exists'), [
                    new Arg(new Variable('normalizer')),
                    new Arg(new PropertyFetch(new Variable('this'), 'normalizersCache')),
                ])),
                [
                    'stmts' => [
                        new Expression(new Assign(new Variable('normalizerClass'), new ArrayDimFetch($factory->classConstFetch('static', 'NORMALIZERS'), new Variable('normalizer')))),
                        new Expression(new Assign(new Variable('instance'), new New_(new Variable('normalizerClass'), [new Arg($factory->propertyFetch(new Variable('this'), 'autoMapper'))])), ['comments' => [BuilderHelpers::normalizeDocComment('/** @var NormalizerInterface&DenormalizerInterface $instance */')]]),
                        new Expression(new Assign(new ArrayDimFetch(new PropertyFetch(new Variable('this'), 'normalizersCache'), new Variable('normalizer')), new Variable('instance'))),
                    ],
                ]
            ),
            new Return_(new ArrayDimFetch(new PropertyFetch(new Variable('this'), 'normalizersCache'), new Variable('normalizer'))),
        ];
    }
}
