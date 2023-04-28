<?php

namespace Jane\Component\JsonSchemaCompiler\Tests;

use Jane\Component\JsonSchemaCompiler\Compiled\Model;
use Jane\Component\JsonSchemaCompiler\Compiled\Property;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\ObjectType;
use Jane\Component\JsonSchemaCompiler\Compiler;
use Jane\Component\JsonSchemaCompiler\CompilerInterface;
use PHPUnit\Framework\TestCase;

class CompilerTest extends TestCase
{
    private CompilerInterface $compiler;

    protected function setUp(): void
    {
        $this->compiler = new Compiler();
    }

    public function testCompiler(): void
    {
        $registry = $this->compiler->fromPath(__DIR__.'/resources/open-banking-tracker.json', rootModel: 'OpenBankingTracker');

        /** @var Model $model */
        $model = $registry->getModel('OpenBankingTracker');

        self::assertInstanceOf(Property::class, $uxProperty = $model->getProperty('ux'));
        self::assertEquals('ux', $uxProperty->name);
        self::assertInstanceOf(ObjectType::class, $uxProperty->type);
        self::assertEquals('OpenBankingTrackerUx', $uxProperty->type->className);
        self::assertInstanceOf(Model::class, $uxModel = $registry->getModel($uxProperty->type->className));
        self::assertEquals('OpenBankingTrackerUx', $uxModel->name);
        self::assertCount(1, $uxModel->properties);
        self::assertEquals('accountOpening', $uxModel->properties[0]->name);
        self::assertInstanceOf(ObjectType::class, $uxModel->properties[0]->type);
        self::assertEquals('OpenBankingTrackerUxAccountOpening', $uxModel->properties[0]->type->className);
    }
}
