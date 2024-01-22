<?php

declare(strict_types=1);

/*
 * This file has been auto generated by Jane,
 *
 * Do not edit it directly.
 */

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\Person\Normalizer;

use AutoMapper\AutoMapper;
use AutoMapper\AutoMapperInterface;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\Person\Model\Person;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PersonNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private readonly AutoMapperInterface $autoMapper;

    public function __construct(AutoMapperInterface $autoMapper = null)
    {
        $this->autoMapper = $autoMapper ?? AutoMapper::create();
    }

    /**
     * @param Person $object
     *
     * @return array
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        /** @var array $output */
        $output = $this->autoMapper->map($object, 'array', $context);

        return $output;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Person;
    }

    /**
     * @param array|object $data
     *
     * @return Person
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        /** @var class-string $class */
        $class = $type;
        /** @var Person $output */
        $output = $this->autoMapper->map($data, $class, $context);

        return $output;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type === Person::class;
    }

    /** @return array<class-string, bool> */
    public function getSupportedTypes(?string $format): array
    {
        return [Person::class => true];
    }
}
