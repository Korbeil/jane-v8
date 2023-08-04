<?php

declare(strict_types=1);

/*
 * This file has been auto generated by Jane,
 *
 * Do no edit it directly.
 */

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Normalizer;

use Jane\Component\AutoMapper\AutoMapper;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTracker;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerAcceleratorProgram;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerApiPerformanceReports;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerApiProducts;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerApiProductsApiSpecs;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerApiProductsRateLimits;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerApiServerEndpoints;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerApiSpecs;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerApiStatusUrls;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerArticles;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerCompliance;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerDataBreaches;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerFinancialReports;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerMobileApps;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerOwnership;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerPartnerships;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerPostmanCollections;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerRewardPartners;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerSandbox;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerSdks;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerUx;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTrackerUxAccountOpening;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class JaneNormalizers implements NormalizerInterface, DenormalizerInterface
{
    public const MODELS = [OpenBankingTrackerMobileApps::class => false, OpenBankingTrackerCompliance::class => false, OpenBankingTrackerSandbox::class => false, OpenBankingTrackerAcceleratorProgram::class => false, OpenBankingTrackerApiServerEndpoints::class => false, OpenBankingTrackerApiStatusUrls::class => false, OpenBankingTrackerApiSpecs::class => false, OpenBankingTrackerApiPerformanceReports::class => false, OpenBankingTrackerApiProductsApiSpecs::class => false, OpenBankingTrackerApiProductsRateLimits::class => false, OpenBankingTrackerApiProducts::class => false, OpenBankingTrackerSdks::class => false, OpenBankingTrackerPostmanCollections::class => false, OpenBankingTrackerPartnerships::class => false, OpenBankingTrackerRewardPartners::class => false, OpenBankingTrackerUxAccountOpening::class => false, OpenBankingTrackerUx::class => false, OpenBankingTrackerFinancialReports::class => false, OpenBankingTrackerOwnership::class => false, OpenBankingTrackerDataBreaches::class => false, OpenBankingTrackerArticles::class => false, OpenBankingTracker::class => false];
    public const NORMALIZERS = [OpenBankingTrackerMobileApps::class => OpenBankingTrackerMobileAppsNormalizer::class, OpenBankingTrackerCompliance::class => OpenBankingTrackerComplianceNormalizer::class, OpenBankingTrackerSandbox::class => OpenBankingTrackerSandboxNormalizer::class, OpenBankingTrackerAcceleratorProgram::class => OpenBankingTrackerAcceleratorProgramNormalizer::class, OpenBankingTrackerApiServerEndpoints::class => OpenBankingTrackerApiServerEndpointsNormalizer::class, OpenBankingTrackerApiStatusUrls::class => OpenBankingTrackerApiStatusUrlsNormalizer::class, OpenBankingTrackerApiSpecs::class => OpenBankingTrackerApiSpecsNormalizer::class, OpenBankingTrackerApiPerformanceReports::class => OpenBankingTrackerApiPerformanceReportsNormalizer::class, OpenBankingTrackerApiProductsApiSpecs::class => OpenBankingTrackerApiProductsApiSpecsNormalizer::class, OpenBankingTrackerApiProductsRateLimits::class => OpenBankingTrackerApiProductsRateLimitsNormalizer::class, OpenBankingTrackerApiProducts::class => OpenBankingTrackerApiProductsNormalizer::class, OpenBankingTrackerSdks::class => OpenBankingTrackerSdksNormalizer::class, OpenBankingTrackerPostmanCollections::class => OpenBankingTrackerPostmanCollectionsNormalizer::class, OpenBankingTrackerPartnerships::class => OpenBankingTrackerPartnershipsNormalizer::class, OpenBankingTrackerRewardPartners::class => OpenBankingTrackerRewardPartnersNormalizer::class, OpenBankingTrackerUxAccountOpening::class => OpenBankingTrackerUxAccountOpeningNormalizer::class, OpenBankingTrackerUx::class => OpenBankingTrackerUxNormalizer::class, OpenBankingTrackerFinancialReports::class => OpenBankingTrackerFinancialReportsNormalizer::class, OpenBankingTrackerOwnership::class => OpenBankingTrackerOwnershipNormalizer::class, OpenBankingTrackerDataBreaches::class => OpenBankingTrackerDataBreachesNormalizer::class, OpenBankingTrackerArticles::class => OpenBankingTrackerArticlesNormalizer::class, OpenBankingTracker::class => OpenBankingTrackerNormalizer::class];
    private readonly ?AutoMapper $autoMapper;
    /** @var (NormalizerInterface&DenormalizerInterface)[] */
    public array $normalizersCache = [];

    public function __construct(AutoMapper $autoMapper = null)
    {
        $this->autoMapper = $autoMapper;
    }

    /**
     * @param object $object
     *
     * @return array
     */
    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        return $this->getNormalizer($object::class)->normalize($object, $format, $context);
    }

    /**
     * @param object $data
     *
     * @return bool
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = [])
    {
        return \in_array($data::class, array_keys(static::MODELS), true);
    }

    /**
     * @param array|object $data
     *
     * @return object
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        return $this->getNormalizer($type)->denormalize($data, $type, $format, $context);
    }

    /** @return bool */
    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = [])
    {
        return \in_array($type, array_keys(static::MODELS), true);
    }

    /** @return array<class-string, bool> */
    public function getSupportedTypes(?string $format): array
    {
        return static::MODELS;
    }

    public function getNormalizer(string $normalizer): NormalizerInterface&DenormalizerInterface
    {
        if (!\array_key_exists($normalizer, $this->normalizersCache)) {
            $normalizerClass = static::NORMALIZERS[$normalizer];
            /** @var NormalizerInterface&DenormalizerInterface $instance */
            $instance = new $normalizerClass($this->autoMapper);
            $this->normalizersCache[$normalizer] = $instance;
        }

        return $this->normalizersCache[$normalizer];
    }
}
