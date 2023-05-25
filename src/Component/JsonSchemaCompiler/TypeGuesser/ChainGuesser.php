<?php

namespace Jane\Component\JsonSchemaCompiler\TypeGuesser;

use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;

class ChainGuesser implements TypeGuesserInterface
{
    public function __construct(
        /** @var TypeGuesserInterface[] $guessers */
        private array $guessers = [],
    ) {
    }

    public function addGuesser(TypeGuesserInterface $guesser): void
    {
        if ($guesser instanceof ChainGuesserAwareInterface) {
            $guesser->setChainGuesser($this);
        }

        $this->guessers[] = $guesser;
    }

    public function guessType(Registry $registry, JsonSchema $schema): Type
    {
        foreach ($this->guessers as $guesser) {
            if (null !== ($guessedType = $guesser->guessType($registry, $schema))) {
                return $guessedType;
            }
        }

        return new Type(Type::MIXED);
    }

    public static function create(): self
    {
        $chainGuesser = new self();
        $chainGuesser->addGuesser(new DateGuesser()); // @fixme configuration !
        $chainGuesser->addGuesser(new DateTimeGuesser()); // @fixme configuration !
        $chainGuesser->addGuesser(new EnumGuesser());
        $chainGuesser->addGuesser(new SimpleTypeGuesser());
        $chainGuesser->addGuesser(new ArrayGuesser());
        $chainGuesser->addGuesser(new MultipleGuesser());
        $chainGuesser->addGuesser(new ObjectGuesser());
        $chainGuesser->addGuesser(new AnyOfGuesser());
        $chainGuesser->addGuesser(new AllOfGuesser());
        $chainGuesser->addGuesser(new OneOfGuesser());
        $chainGuesser->addGuesser(new PatternPropertiesGuesser());
        $chainGuesser->addGuesser(new AdditionalItemsGuesser());
        $chainGuesser->addGuesser(new AdditionalPropertiesGuesser());

        return $chainGuesser;
    }
}
