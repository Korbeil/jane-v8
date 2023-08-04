<?php

declare(strict_types=1);

/*
 * This file has been auto generated by Jane,
 *
 * Do no edit it directly.
 */

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Normalizer;

use Jane\Component\AutoMapper\AutoMapper;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerApiServerEndpoints;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class OpenBankingTrackerApiServerEndpointsNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private readonly AutoMapper $autoMapper;

    public function __construct(AutoMapper $autoMapper = null)
    {
        $this->autoMapper = $autoMapper ?? AutoMapper::create();
    }

    /**
     * @param OpenBankingTrackerApiServerEndpoints $object
     *
     * @return array
     */
    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        /** @var array $output */
        $output = $this->autoMapper->map($object, 'array', $context);

        return $output;
    }

    /** @return bool */
    public function supportsNormalization(mixed $data, string $format = null, array $context = [])
    {
        return $data instanceof OpenBankingTrackerApiServerEndpoints;
    }

    /**
     * @param array|object $data
     *
     * @return OpenBankingTrackerApiServerEndpoints
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        /** @var OpenBankingTrackerApiServerEndpoints $output */
        $output = $this->autoMapper->map($data, $type, $context);

        return $output;
    }

    /** @return bool */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = [])
    {
        return $type === OpenBankingTrackerApiServerEndpoints::class;
    }

    /** @return array<class-string, bool> */
    public function getSupportedTypes(?string $format): array
    {
        return [OpenBankingTrackerApiServerEndpoints::class => true];
    }
}
