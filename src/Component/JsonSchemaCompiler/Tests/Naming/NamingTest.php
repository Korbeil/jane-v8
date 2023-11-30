<?php

namespace Jane\Component\JsonSchemaCompiler\Tests\Naming;

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
        yield ['contact', 'Contact1'];
        yield ['$contact', 'DollarContact'];
        yield ['contact_address', 'ContactAddress'];
        yield ['contact_étendu', 'ContactEtendu'];
        yield ['carnet d\'adresses', 'CarnetDAdresses'];
        yield ['final', '_Final'];
        yield ['ŠšŽžÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýþÿĞİŞğışü', 'SsZzAAAAAeAaAeCEEEEIIIINOOOOOeOUUUUeYSsaaaaaeaaaceeeeiiiiNoooooeouuuyYGISgisue'];
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

    /**
     * @param string|int|float $input
     *
     * @dataProvider getEnumCaseNameProvider
     */
    public function testGetEnumCaseName($input, string $expected): void
    {
        self::assertEquals($expected, $this->naming->getEnumCaseName($input));
    }

    public static function getEnumCaseNameProvider(): \Generator
    {
        yield ['test', 'TEST'];
        yield ['TEST', 'TEST'];
        yield ['TEST in CAPSLOCK', 'TEST_IN_CAPSLOCK'];
        yield ['test-with-dashes', 'TEST_WITH_DASHES'];
        yield ['test with spaces', 'TEST_WITH_SPACES'];
        yield ['testCamelCase', 'TEST_CAMEL_CASE'];
        yield ['2testStartsWithNumber', 'N2TEST_STARTS_WITH_NUMBER'];
        yield ['test with 2', 'TEST_WITH2'];
        yield ['test with $ * / ùéài', 'TEST_WITH_UEAI'];
        yield [1.43, 'VALUE1_43'];
        yield [143, 'VALUE143'];
    }
}
