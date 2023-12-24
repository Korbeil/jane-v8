<?php

declare(strict_types=1);

/*
 * This file has been auto generated by Jane,
 *
 * Do not edit it directly.
 */

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Normalizer;

use AutoMapper\AutoMapper;
use AutoMapper\AutoMapperInterface;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerAcceleratorProgram;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class OpenBankingTrackerAcceleratorProgramNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private readonly AutoMapperInterface $autoMapper;

    public function __construct(AutoMapperInterface $autoMapper = null)
    {
        $this->autoMapper = $autoMapper ?? AutoMapper::create();
    }

    /**
     * @param OpenBankingTrackerAcceleratorProgram $object
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
        return $data instanceof OpenBankingTrackerAcceleratorProgram;
    }

    /**
     * @param array|object $data
     *
     * @return OpenBankingTrackerAcceleratorProgram
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        /** @var class-string $class */
        $class = $type;
        /** @var OpenBankingTrackerAcceleratorProgram $output */
        $output = $this->autoMapper->map($data, $class, $context);

        return $output;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type === OpenBankingTrackerAcceleratorProgram::class;
    }

    /** @return array<class-string, bool> */
    public function getSupportedTypes(?string $format): array
    {
        return [OpenBankingTrackerAcceleratorProgram::class => true];
    }
}