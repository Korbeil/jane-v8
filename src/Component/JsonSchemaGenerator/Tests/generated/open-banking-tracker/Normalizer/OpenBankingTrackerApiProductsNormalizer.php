<?php

declare(strict_types=1);

/*
 * This file has been auto generated by Jane,
 *
 * Do not edit it directly.
 */

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Normalizer;

use AutoMapper\AutoMapper;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerApiProducts;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class OpenBankingTrackerApiProductsNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private readonly AutoMapper $autoMapper;

    public function __construct(AutoMapper $autoMapper = null)
    {
        $this->autoMapper = $autoMapper ?? AutoMapper::create();
    }

    /**
     * @param OpenBankingTrackerApiProducts $object
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
        return $data instanceof OpenBankingTrackerApiProducts;
    }

    /**
     * @param array|object $data
     *
     * @return OpenBankingTrackerApiProducts
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        /** @var class-string $class */
        $class = $type;
        /** @var OpenBankingTrackerApiProducts $output */
        $output = $this->autoMapper->map($data, $class, $context);

        return $output;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type === OpenBankingTrackerApiProducts::class;
    }

    /** @return array<class-string, bool> */
    public function getSupportedTypes(?string $format): array
    {
        return [OpenBankingTrackerApiProducts::class => true];
    }
}
