<?php

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model;

class OpenBankingTracker
{
    public function __construct(
        public string $id,
        public string $parentId,
        public bool $bankingGroup,
        public bool $bankingGroupId,
        public float $numberOfBanks,
        /** @var OpenBankingTrackerTypeEnum[] */
        public OpenBankingTrackerTypeEnum[] $type,
        /** @var OpenBankingTrackerBankTypeEnum[] */
        public OpenBankingTrackerBankTypeEnum[] $bankType,
        public OpenBankingTrackerStatusEnum $status,
        public string $name,
        public string $desription,
        public string $bic,
        public string|null $wikipediaUrl,
        public string|null $legalName,
        public bool $verified,
        public string $icon,
        public string $websiteUrl,
        public string $countryHQ,
        /** @var mixed[] */
        public mixed[] $countries,
        /** @var string|mixed[]|null */
        public string|mixed[]|null $thirdPartyBankingLicense,
        public string|null $debitAccountLicense,
        /** @var mixed[] */
        public mixed[] $debitCards,
        /** @var mixed[] */
        public mixed[] $creditCards,
        /** @var mixed[] */
        public mixed[] $virtualCards,
        public bool $webApplication,
        /** @var OpenBankingTrackerMobileApps[] */
        public OpenBankingTrackerMobileApps[] $mobileApps,
        /** @var OpenBankingTrackerCompliance[]|null */
        public OpenBankingTrackerCompliance[]|null $compliance,
        public OpenBankingTrackerSandbox $sandbox,
        public string|null $developerPortalUrl,
        public string|null $developerSuccesStoriesUrl,
        /** @var OpenBankingTrackerTppAccessInterfaceEnum[] */
        public OpenBankingTrackerTppAccessInterfaceEnum[] $tppAccessInterface,
        /** @var OpenBankingTrackerApiAggregatorsEnum[]|null */
        public OpenBankingTrackerApiAggregatorsEnum[]|null $apiAggregators,
        /** @var OpenBankingTrackerCollectionsEnum[]|null */
        public OpenBankingTrackerCollectionsEnum[]|null $collections,
        public string|null $openBankProjectUrl,
        public string|null $developerCommunityUrl,
        public bool $slackCommunity,
        public string|null $acceleratorProgramUrl,
        public null|OpenBankingTrackerAcceleratorProgram $acceleratorProgram,
        /** @var OpenBankingTrackerApiGatewaysEnum[]|null */
        public OpenBankingTrackerApiGatewaysEnum[]|null $apiGateways,
        /** @var null|OpenBankingTrackerApiAuthEnum[] */
        public null|OpenBankingTrackerApiAuthEnum[] $apiAuth,
        public string|null $apiChangelogUrl,
        public string|null $apiReferenceUrl,
        /** @var OpenBankingTrackerApiStandardsEnum[] */
        public OpenBankingTrackerApiStandardsEnum[] $apiStandards,
        /** @var OpenBankingTrackerApiServerEndpoints[] */
        public OpenBankingTrackerApiServerEndpoints[] $apiServerEndpoints,
        public OpenBankingTrackerApiAccessEnum|null $apiAccess,
        public OpenBankingTrackerApiAccessRequestUrlEnum|null $apiAccessRequestUrl,
        /** @var OpenBankingTrackerApiStatusUrls[]|null */
        public OpenBankingTrackerApiStatusUrls[]|null $apiStatusUrls,
        public string|null $totalApiProducts,
        public string|null $developerContactEmail,
        /** @var OpenBankingTrackerApiSpecs[] */
        public OpenBankingTrackerApiSpecs[] $apiSpecs,
        /** @var null|OpenBankingTrackerApiPerformanceReports[] */
        public null|OpenBankingTrackerApiPerformanceReports[] $apiPerformanceReports,
        /** @var null|OpenBankingTrackerApiProducts[] */
        public null|OpenBankingTrackerApiProducts[] $apiProducts,
        /** @var OpenBankingTrackerSdks[]|null */
        public OpenBankingTrackerSdks[]|null $sdks,
        /** @var OpenBankingTrackerPostmanCollections[]|null */
        public OpenBankingTrackerPostmanCollections[]|null $postmanCollections,
        public string|null $apiMarketplaceUrl,
        public null|OpenBankingTrackerPartnerships $partnerships,
        public null|OpenBankingTrackerRewardPartners $rewardPartners,
        public OpenBankingTrackerUx $ux,
        public string|null $twitter,
        public string|null $github,
        public string|null $crunchbase,
        public string|null $fca,
        public string|null $legalEntityIdentifier,
        public string|null $swiftCode,
        public OpenBankingTrackerIpoStatusEnum $ipoStatus,
        public string|null $stockSymbol,
        public string|null $investorRelationsUrl,
        /** @var null|OpenBankingTrackerFinancialReports[] */
        public null|OpenBankingTrackerFinancialReports[] $financialReports,
        /** @var OpenBankingTrackerOwnership[] */
        public OpenBankingTrackerOwnership[] $ownership,
        public bool $stateOwned,
        /** @var OpenBankingTrackerDataBreaches[] */
        public OpenBankingTrackerDataBreaches[] $dataBreaches,
        /** @var null|OpenBankingTrackerArticles[] */
        public null|OpenBankingTrackerArticles[] $articles
    )
    {
    }
}