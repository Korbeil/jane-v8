<?php

declare(strict_types=1);

/*
 * This file has been auto generated by Jane,
 *
 * Do no edit it directly.
 */

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Normalizer;

use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerPostmanCollections;

class OpenBankingTrackerPostmanCollectionsNormalizer
{
    /** @param OpenBankingTrackerPostmanCollections $object */
    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        $data = [];
        $data['githubUrl'] = $object->githubUrl;

        return $data;
    }

    /** @return bool */
    public function supportsNormalization(mixed $data, string $format = null, array $context = [])
    {
        return $data instanceof OpenBankingTrackerPostmanCollections;
    }

    /** @return OpenBankingTrackerPostmanCollections */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        $object->githubUrl = $data['githubUrl'];

        return $object;
    }

    /** @return bool */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = [])
    {
        return $type === OpenBankingTrackerPostmanCollections::class;
    }

    /** @return array<class-string, bool> */
    public function getSupportedTypes(?string $format): array
    {
        return [OpenBankingTrackerPostmanCollections::class => true];
    }
}
