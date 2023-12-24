<?php

declare(strict_types=1);

/*
 * This file has been auto generated by Jane,
 *
 * Do not edit it directly.
 */

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model;

class OpenBankingTracker
{
    public function __construct(
        public string $id,
        public string|null $parentId,
        public bool|null $bankingGroup,
        public bool|null $bankingGroupId,
        public float|null $numberOfBanks,
        /** @var OpenBankingTrackerTypeEnum[] */
        public array $type,
        /** @var OpenBankingTrackerBankTypeEnum[]|null */
        public array|null $bankType,
        public OpenBankingTrackerStatusEnum|null $status,
        public string $name,
        public string|null $description,
        public string|null $bic,
        public string|null $wikipediaUrl,
        public string|null $legalName,
        public bool $verified,
        public string $icon,
        public string $websiteUrl,
        public string $countryHQ,
        /** @var mixed[] */
        public array $countries,
        /** @var string|mixed[]|null */
        public string|array|null $thirdPartyBankingLicense,
        public string|null $debitAccountLicense,
        /** @var mixed[]|null */
        public array|null $debitCards,
        /** @var mixed[]|null */
        public array|null $creditCards,
        /** @var mixed[]|null */
        public array|null $virtualCards,
        public bool $webApplication,
        /** @var array<string, OpenBankingTrackerMobileApps> */
        public array $mobileApps,
        /** @var OpenBankingTrackerCompliance[]|null */
        public array|null $compliance,
        public OpenBankingTrackerSandbox|null $sandbox,
        public string|null $developerPortalUrl,
        public string|null $developerSuccesStoriesUrl,
        /** @var OpenBankingTrackerTppAccessInterfaceEnum[]|null */
        public array|null $tppAccessInterface,
        /** @var OpenBankingTrackerApiAggregatorsEnum[]|null */
        public array|null $apiAggregators,
        /** @var OpenBankingTrackerCollectionsEnum[]|null */
        public array|null $collections,
        public string|null $openBankProjectUrl,
        public string|null $developerCommunityUrl,
        public bool|null $slackCommunity,
        public string|null $acceleratorProgramUrl,
        public null|OpenBankingTrackerAcceleratorProgram $acceleratorProgram,
        /** @var OpenBankingTrackerApiGatewaysEnum[]|null */
        public array|null $apiGateways,
        /** @var OpenBankingTrackerApiAuthEnum[]|null */
        public null|array $apiAuth,
        public string|null $apiChangelogUrl,
        public string|null $apiReferenceUrl,
        /** @var OpenBankingTrackerApiStandardsEnum[] */
        public array $apiStandards,
        /** @var OpenBankingTrackerApiServerEndpoints[]|null */
        public array|null $apiServerEndpoints,
        public OpenBankingTrackerApiAccessEnum|null $apiAccess,
        public OpenBankingTrackerApiAccessRequestUrlEnum|null $apiAccessRequestUrl,
        /** @var OpenBankingTrackerApiStatusUrls[]|null */
        public array|null $apiStatusUrls,
        public string|null $totalApiProducts,
        public string|null $developerContactEmail,
        /** @var OpenBankingTrackerApiSpecs[]|null */
        public array|null $apiSpecs,
        /** @var OpenBankingTrackerApiPerformanceReports[]|null */
        public null|array $apiPerformanceReports,
        /** @var OpenBankingTrackerApiProducts[]|null */
        public null|array $apiProducts,
        /** @var OpenBankingTrackerSdks[]|null */
        public array|null $sdks,
        /** @var OpenBankingTrackerPostmanCollections[]|null */
        public array|null $postmanCollections,
        public string|null $apiMarketplaceUrl,
        /** @var OpenBankingTrackerPartnerships[]|null */
        public null|array $partnerships,
        /** @var OpenBankingTrackerRewardPartners[]|null */
        public null|array $rewardPartners,
        public OpenBankingTrackerUx|null $ux,
        public string|null $twitter,
        public string|null $github,
        public string|null $crunchbase,
        public string|null $fca,
        public string|null $legalEntityIdentifier,
        public string|null $swiftCode,
        public OpenBankingTrackerIpoStatusEnum|null $ipoStatus,
        public string|null $stockSymbol,
        public string|null $investorRelationsUrl,
        /** @var OpenBankingTrackerFinancialReports[]|null */
        public null|array $financialReports,
        /** @var OpenBankingTrackerOwnership[] */
        public array $ownership,
        public bool $stateOwned,
        /** @var OpenBankingTrackerDataBreaches[]|null */
        public array|null $dataBreaches,
        /** @var OpenBankingTrackerArticles[]|null */
        public null|array $articles
    ) {
    }
}