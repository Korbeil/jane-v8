<?php

namespace Jane\Component\JsonSchemaCompiler\Tests\Naming;

use Jane\Component\JsonSchemaCompiler\Compiled\Model;
use Jane\Component\JsonSchemaCompiler\Compiled\ModelProperty;
use Jane\Component\JsonSchemaCompiler\Compiled\PropertyType;
use Jane\Component\JsonSchemaCompiler\Naming\Naming;
use Jane\Component\JsonSchemaCompiler\Naming\NamingInterface;
use PHPUnit\Framework\TestCase;

class NamingTest extends TestCase
{
    private NamingInterface $naming;

    protected function setUp(): void
    {
        parent::setUp();
        $this->naming = new Naming();
    }

    /**
     * @dataProvider getModelNameProvider
     */
    public function testGetModelName(string $input, string $expected): void
    {
        self::assertEquals($expected, $this->naming->getModelName($input));
    }

    public static function getModelNameProvider(): \Generator
    {
        yield ['Contact', 'Contact'];
        yield ['contact', 'Contact'];
        yield ['$contact', 'DollarContact'];
        yield ['contact_address', 'ContactAddress'];
        yield ['contact_étendu', 'ContactEtendu'];
        yield ['carnet d\'adresses', 'CarnetDAdresses'];
        yield ['final', '_Final'];
    }

    /**
     * @dataProvider getPropertyNameProvider
     */
    public function testGetPropertyName(string $input, ?Model $model, string $expected): void
    {
        self::assertEquals($expected, $this->naming->getPropertyName($input, $model));
    }

    public static function getPropertyNameProvider(): \Generator
    {
        yield ['contact', null, 'contact'];
        $model = new Model('Company');
        $model->addProperty(new ModelProperty('contact', type: [PropertyType::OBJECT]));
        $model->addProperty(new ModelProperty('contact1', type: [PropertyType::OBJECT]));
        yield ['contact', $model, 'contact2'];
        yield ['contact_address', null, 'contactAddress'];
        yield ['0contact', null, 'n0contact'];
        yield ['$contact', null, 'dollarContact'];
    }
}
