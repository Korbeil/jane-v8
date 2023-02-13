<?php

namespace Jane\Component\JsonSchemaMetadata\Tests;

use Jane\Component\JsonSchemaMetadata\Compiler;
use PHPUnit\Framework\TestCase;

class CompilerTest extends TestCase
{
    private Compiler $compiler;

    protected function setUp(): void
    {
        $this->compiler = new Compiler();
    }

    public function testWithSimpleJsonSchema(): void
    {
        $metadatas = $this->compiler->compile(__DIR__.'/resources/schema.json');

        var_dump($metadatas);
    }
}
