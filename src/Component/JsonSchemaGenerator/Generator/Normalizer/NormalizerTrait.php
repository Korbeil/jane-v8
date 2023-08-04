<?php

namespace Jane\Component\JsonSchemaGenerator\Generator\Normalizer;

use Jane\Component\JsonSchemaCompiler\Compiled\Model;
use Jane\Component\JsonSchemaCompiler\Compiled\Property;
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;

trait NormalizerTrait
{
    /**
     * @return Stmt[]
     */
    public function normalizeStatements(Model $model): array
    {
        $factory = new BuilderFactory();
        $dataVariable = $factory->var('data');

        $normalizeStmts = [
            new Assign($dataVariable, new Array_()),
        ];
        foreach ($model->getPropertyNames() as $propertyName) {
            $property = $model->getProperty($propertyName);
            if (null === $property) {
                continue;
            }

            $normalizeStmts[] = $this->makeNormalize($property);
        }

        $normalizeStmts = array_merge($normalizeStmts, [
            new Return_($dataVariable),
        ]);

        return $normalizeStmts;
    }

    /**
     * @return Stmt[]
     */
    public function supportNormalizationStatements(Model $model): array
    {
        $factory = new BuilderFactory();

        return [new Return_(new Instanceof_($factory->var('data'), new Name($model->name)))];
    }

    private function makeNormalize(Property $property): Expression
    {
        $factory = new BuilderFactory();
        $objectVariable = $factory->var('object');
        $dataVariable = $factory->var('data');

        return new Expression(new Assign(
            new ArrayDimFetch($dataVariable, new String_($property->name)),
            $factory->propertyFetch($objectVariable, $property->phpName),
        ));
    }
}
