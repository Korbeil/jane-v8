<?php

namespace Jane\Component\JsonSchemaGenerator\Tests;

use Jane\Component\JsonSchemaGenerator\Configuration;
use Jane\Component\JsonSchemaGenerator\Generator;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    public function testOpenBankingTracker(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/generated/open-banking-tracker/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\OpenBankingTracker',
        ));
        $generator->fromPath(__DIR__.'/resources/open-banking-tracker.json', 'OpenBankingTracker');
    }
}
