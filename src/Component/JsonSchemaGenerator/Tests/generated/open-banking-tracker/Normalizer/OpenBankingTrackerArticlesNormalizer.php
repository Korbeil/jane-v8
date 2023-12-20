<?php

declare(strict_types=1);

/*
 * This file has been auto generated by Jane,
 *
 * Do not edit it directly.
 */

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Normalizer;

use AutoMapper\AutoMapper;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerArticles;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class OpenBankingTrackerArticlesNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private readonly AutoMapper $autoMapper;

    public function __construct(AutoMapper $autoMapper = null)
    {
        $this->autoMapper = $autoMapper ?? AutoMapper::create();
    }

    /**
     * @param OpenBankingTrackerArticles $object
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
        return $data instanceof OpenBankingTrackerArticles;
    }

    /**
     * @param array|object $data
     *
     * @return OpenBankingTrackerArticles
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        /** @var class-string $class */
        $class = $type;
        /** @var OpenBankingTrackerArticles $output */
        $output = $this->autoMapper->map($data, $class, $context);

        return $output;
    }

    /** @return bool */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = [])
    {
        return $type === OpenBankingTrackerArticles::class;
    }

    /** @return array<class-string, bool> */
    public function getSupportedTypes(?string $format): array
    {
        return [OpenBankingTrackerArticles::class => true];
    }
}
