<?php

namespace Jane\Component\JsonSchemaMetadata\Tests\Naming;

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
        yield ['contact', 'Contact1'];
        yield ['$contact', 'DollarContact'];
        yield ['contact_address', 'ContactAddress'];
        yield ['contact_étendu', 'ContactEtendu'];
        yield ['carnet d\'adresses', 'CarnetDAdresses'];
        yield ['final', '_Final'];
    }

    /**
     * @dataProvider getPropertyNameProvider
     */
    public function testGetPropertyName(string $input, ?string $model, string $expected): void
    {
        self::assertEquals($expected, $this->naming->getPropertyName($input, $model));
    }

    public static function getPropertyNameProvider(): \Generator
    {
        yield ['contact', null, 'contact'];
        yield ['contact', 'Company', 'contact'];
        yield ['contact', 'Company', 'contact1'];
        yield ['contact', 'Company', 'contact2'];
        yield ['contact_address', null, 'contactAddress'];
        yield ['0contact', null, 'n0contact'];
        yield ['$contact', null, 'dollarContact'];
    }
}
