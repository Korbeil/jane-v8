<?php

namespace Jane\Component\JsonSchemaCompiler\Tests;

use Jane\Component\JsonSchemaCompiler\Compiled\Model;
use Jane\Component\JsonSchemaCompiler\Compiled\Property;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\ArrayType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\EnumType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\MultipleType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\ObjectType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
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

        $model = $registry->getModel('OpenBankingTracker');
        self::assertNotNull($model);
        self::assertInstanceOf(Property::class, $uxProperty = $model->getProperty('ux'));
        self::assertEquals('ux', $uxProperty->name);
        self::assertInstanceOf(MultipleType::class, $uxProperty->type);
        self::assertEquals(new MultipleType([new ObjectType('OpenBankingTrackerUx'), new Type(Type::NULL)]), $uxProperty->type);
        self::assertInstanceOf(ObjectType::class, $uxProperty->type->types[0]);
        self::assertInstanceOf(Model::class, $uxModel = $registry->getModel($uxProperty->type->types[0]->className));
        self::assertEquals('OpenBankingTrackerUx', $uxModel->name);
        self::assertCount(1, $uxModel->properties);
        self::assertEquals('accountOpening', $uxModel->properties[0]->name);
        self::assertInstanceOf(ObjectType::class, $uxModel->properties[0]->type);
        self::assertEquals('OpenBankingTrackerUxAccountOpening', $uxModel->properties[0]->type->className);
        self::assertInstanceOf(Property::class, $apiStandardsProperty = $model->getProperty('apiStandards'));
        self::assertInstanceOf(ArrayType::class, $apiStandardsProperty->type);
        self::assertInstanceOf(EnumType::class, $apiStandardsProperty->type->itemsType);
        self::assertEquals([
            'OBIE' => 'OBIE',
            'STET' => 'STET',
            'BERLIN' => 'BERLIN',
            'BISTRA' => 'BISTRA',
            'POLISHAPI' => 'POLISHAPI',
            'SIX_B_LINK' => 'SIX-B-LINK',
        ], $apiStandardsProperty->type->itemsType->values);
        self::assertInstanceOf(Property::class, $apiAccessProperty = $model->getProperty('apiAccess'));
        self::assertInstanceOf(MultipleType::class, $apiAccessProperty->type);
        self::assertInstanceOf(EnumType::class, $apiAccessProperty->type->types[0]);
        self::assertEquals(Type::STRING, $apiAccessProperty->type->types[0]->type);
        self::assertInstanceOf(Type::class, $apiAccessProperty->type->types[1]);
        self::assertEquals(Type::NULL, $apiAccessProperty->type->types[1]->type);
    }

    public function testNamingDuplicates(): void
    {
        $registry = $this->compiler->fromPath(__DIR__.'/resources/open-banking-tracker-with-duplicate.json', rootModel: 'OpenBankingTracker');

        $model = $registry->getModel('OpenBankingTracker');
        self::assertNotNull($model);
        self::assertInstanceOf(Property::class, $sandboxProperty = $model->getProperty('sandbox'));
        self::assertInstanceOf(ObjectType::class, $sandboxProperty->type);
        self::assertEquals('OpenBankingTrackerSandbox1', $sandboxProperty->type->className);

        $model = $registry->getModel('OpenBankingTrackerSandbox');
        self::assertNotNull($model);
        self::assertCount(0, $model->properties);

        $model = $registry->getModel('OpenBankingTrackerSandbox1');
        self::assertNotNull($model);
        self::assertCount(4, $model->properties);
        self::assertEquals('status', $model->properties[0]->name);
        self::assertEquals('$status', $model->properties[1]->name);
        self::assertEquals('dollarStatus', $model->properties[1]->phpName);
        self::assertEquals('status..', $model->properties[2]->name);
        self::assertEquals('status1', $model->properties[2]->phpName);
        self::assertEquals('sourceUrl', $model->properties[3]->name);
        self::assertEquals('sourceUrl', $model->properties[3]->phpName);
    }
}
