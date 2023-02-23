<?php

namespace Jane\Component\JsonSchemaMetadata\Tests\Metadata;

use Jane\Component\JsonSchemaMetadata\Metadata\Reference;
use Jane\Component\JsonSchemaParser\Parser;
use PHPUnit\Framework\TestCase;

class ReferenceTest extends TestCase
{
    /**
     * @dataProvider resolveProvider
     *
     * @param string|JsonSchemaDefinition $expected
     */
    public function testResolve(string $reference, string $origin, string|array $expected): void
    {
        $reference = new Reference($reference, $origin);

        self::assertEquals($expected, $reference->resolve());
    }

    /**
     * @return \Generator<int, array{0: string, 1: string, 2: string|JsonSchemaDefinition}>
     */
    public static function resolveProvider(): \Generator
    {
        /** @var JsonSchemaDefinition $parsed */
        $parsed = (new Parser())->parse(__DIR__.'/../resources/schema.json');

        yield ['#', __DIR__.'/../resources/schema.json', $parsed];
        yield ['http://json-schema.org/draft-07/schema#/$id', __DIR__.'/../resources/schema.json', 'http://json-schema.org/draft-07/schema#'];
    }
}
