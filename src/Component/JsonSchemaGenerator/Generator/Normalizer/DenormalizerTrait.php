<?php

namespace Jane\Component\JsonSchemaGenerator\Generator\Normalizer;

use Jane\Component\JsonSchemaCompiler\Compiled\Model;
use Jane\Component\JsonSchemaCompiler\Compiled\Property;
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;

trait DenormalizerTrait
{
    /**
     * @return Stmt[]
     */
    public function denormalizeStatements(Model $model): array
    {
        $factory = new BuilderFactory();
        $objectVariable = $factory->var('object');
        $dataVariable = $factory->var('data');

        $denormalizeStmts = [];
        foreach ($model->getPropertyNames() as $propertyName) {
            $property = $model->getProperty($propertyName);
            if (null === $property) {
                continue;
            }

            $denormalizeStmts[] = $this->makeDenormalize($property);
        }

        $denormalizeStmts = array_merge($denormalizeStmts, [
            new Return_($objectVariable),
        ]);

        return $denormalizeStmts;
    }

    /**
     * @return Stmt[]
     */
    public function supportDenormalizationStatements(Model $model): array
    {
        $factory = new BuilderFactory();

        return [new Return_(new Identical($factory->var('type'), $factory->classConstFetch($model->name, 'class')))];
    }

    private function makeDenormalize(Property $property): Expression
    {
        $factory = new BuilderFactory();
        $objectVariable = $factory->var('object');
        $dataVariable = $factory->var('data');

        return new Expression(new Assign(
            $factory->propertyFetch($objectVariable, $property->phpName),
            new ArrayDimFetch($dataVariable, new String_($property->name)),
        ));
    }
}
