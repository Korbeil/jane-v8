<?php

namespace Jane\Component\JsonSchemaCompiler\Tests\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\ChainGuesser;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\ChainGuesserAwareInterface;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\EnumGuesser;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\ObjectGuesser;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\TypeGuesserInterface;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use PHPUnit\Framework\TestCase;

abstract class AbstractGuesserTester extends TestCase
{
    protected Registry $registry;
    protected TypeGuesserInterface $guesser;

    protected function setUp(): void
    {
        $this->registry = new Registry();
        $chainGuesser = ChainGuesser::create();

        if ($this->guesser instanceof ChainGuesserAwareInterface) {
            if ($this->guesser instanceof ObjectGuesser || $this->guesser instanceof EnumGuesser) {
                $this->guesser->setChainGuesser($chainGuesser, clearNaming: true);

                return;
            }
            $this->guesser->setChainGuesser($chainGuesser);
        }
    }

    /**
     * @dataProvider providerData
     */
    public function testGuesser(JsonSchema $schema, ?Type $expected): void
    {
        self::assertEquals($expected, $this->guesser->guessType($this->registry, $schema));
    }

    abstract public static function providerData(): \Generator;
}
