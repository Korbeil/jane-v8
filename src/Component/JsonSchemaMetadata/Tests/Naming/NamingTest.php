<?php

namespace Jane\Component\JsonSchemaMetadata\Tests\Naming;

use Jane\Component\JsonSchemaCompiler\Compiled\Model;
use Jane\Component\JsonSchemaCompiler\Compiled\Property;
use Jane\Component\JsonSchemaMetadata\Naming\Naming;
use Jane\Component\JsonSchemaMetadata\Naming\NamingInterface;
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
        $model->addProperty(new Property('contact', 'contact'));
        $model->addProperty(new Property('contact1', 'contact1'));
        yield ['contact', $model, 'contact2'];
        yield ['contact_address', null, 'contactAddress'];
        yield ['0contact', null, 'n0contact'];
        yield ['$contact', null, 'dollarContact'];
    }
}
