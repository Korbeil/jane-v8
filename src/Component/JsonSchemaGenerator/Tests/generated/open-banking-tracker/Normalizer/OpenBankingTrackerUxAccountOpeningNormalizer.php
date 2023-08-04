<?php

declare(strict_types=1);

/*
 * This file has been auto generated by Jane,
 *
 * Do no edit it directly.
 */

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Normalizer;

use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerUxAccountOpening;

class OpenBankingTrackerUxAccountOpeningNormalizer
{
    /** @param OpenBankingTrackerUxAccountOpening $object */
    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        $data = [];
        $data['openAccountViaApp'] = $object->openAccountViaApp;
        $data['numberOfworkingDaysTillActiveAccount'] = $object->numberOfworkingDaysTillActiveAccount;
        $data['numberOfClicksToCreateAccount'] = $object->numberOfClicksToCreateAccount;
        $data['instantAccessToApplePay'] = $object->instantAccessToApplePay;
        $data['digitalIdVerification'] = $object->digitalIdVerification;
        $data['asksLimitedAddressHistory'] = $object->asksLimitedAddressHistory;
        $data['brandedWelcomeLetter'] = $object->brandedWelcomeLetter;

        return $data;
    }

    /** @return bool */
    public function supportsNormalization(mixed $data, string $format = null, array $context = [])
    {
        return $data instanceof OpenBankingTrackerUxAccountOpening;
    }

    /** @return OpenBankingTrackerUxAccountOpening */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        $object->openAccountViaApp = $data['openAccountViaApp'];
        $object->numberOfworkingDaysTillActiveAccount = $data['numberOfworkingDaysTillActiveAccount'];
        $object->numberOfClicksToCreateAccount = $data['numberOfClicksToCreateAccount'];
        $object->instantAccessToApplePay = $data['instantAccessToApplePay'];
        $object->digitalIdVerification = $data['digitalIdVerification'];
        $object->asksLimitedAddressHistory = $data['asksLimitedAddressHistory'];
        $object->brandedWelcomeLetter = $data['brandedWelcomeLetter'];

        return $object;
    }

    /** @return bool */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = [])
    {
        return $type === OpenBankingTrackerUxAccountOpening::class;
    }

    /** @return array<class-string, bool> */
    public function getSupportedTypes(?string $format): array
    {
        return [OpenBankingTrackerUxAccountOpening::class => true];
    }
}
