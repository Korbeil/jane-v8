<?php

namespace Jane\Component\JsonSchemaGenerator\Tests;

use Jane\Component\JsonSchemaGenerator\Runtime\AdditionalAndPatternProperties;
use Jane\Component\JsonSchemaGenerator\Runtime\AdditionalPropertiesInterface;
use Jane\Component\JsonSchemaGenerator\Runtime\PatternPropertiesInterface;
use PHPUnit\Framework\TestCase;

class AdditionalAndPatternPropertiesTest extends TestCase
{
    public function testPatternProperties(): void
    {
        $model = new P_Model();
        $model->S_test = 'foo';

        self::assertNull($model->N_akawaka);
        self::assertEquals('foo', $model->S_test);
    }

    public function testInvalidSetPatternProperties(): void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('Invalid property');

        $model = new P_Model();
        $model->A_foo = 'test';
    }

    public function testInvalidGetPatternProperties(): void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('Invalid property or non-initialized property');

        $model = new P_Model();
        $model->S_foo;
    }

    public function testAdditionalProperties(): void
    {
        $model = new AO_Model();
        $model->foo = 'foo';

        self::assertEquals('foo', $model->foo);
        self::assertEquals('AO', $model->bar);

        $model = new AT_Model();
        $model->foo = 'foo';

        self::assertEquals('foo', $model->foo);
    }

    public function testInvalidGetAdditionalProperties(): void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('Invalid property or non-initialized property');

        $model = new AT_Model();
        $model->fooProperty;
    }
}

class P_Model implements PatternPropertiesInterface
{
    use AdditionalAndPatternProperties;
    public const PATTERN_PROPERTIES_RULES = '{"^S_":{"type":"string"},"^I_":{"type":"integer"},"^N_":{"type":["string","null"],"default":null}}';
}

class AO_Model implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    public const ADDITIONAL_PROPERTIES_RULES = '{"type":"string","default":"AO"}';
}

class AT_Model implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    public const ADDITIONAL_PROPERTIES_RULES = 'true';
}
