<?php

namespace Jane\Component\JsonSchemaGenerator\Tests;

use Jane\Component\JsonSchemaGenerator\Configuration;
use Jane\Component\JsonSchemaGenerator\Generator;
use PHPUnit\Framework\TestCase;

class GeneratorExampleTest extends TestCase
{
    public function testGenerate(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/Person/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\Person',
            useFixer: true,
        ));
        $generator->fromPath(__DIR__.'/Resources/person.json', 'Person');

        self::assertFileExists(__DIR__.'/Generated/Person/Model/Person.php');
    }
}
