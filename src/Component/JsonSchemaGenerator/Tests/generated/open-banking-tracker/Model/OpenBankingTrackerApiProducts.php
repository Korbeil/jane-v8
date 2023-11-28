<?php

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model;

class OpenBankingTrackerApiProducts
{
    public function __construct(
        public string $label,
        public OpenBankingTrackerApiProductsTypeEnum $type,
        /** @var OpenBankingTrackerApiProductsCategoriesEnum[] */
        public OpenBankingTrackerApiProductsCategoriesEnum[] $categories,
        public OpenBankingTrackerApiProductsRegulationEnum $regulation,
        public OpenBankingTrackerApiProductsSpecificationEnum $specification,
        public string|null $description,
        public string|null $documentationUrl,
        public string|null $apiReferenceUrl,
        /** @var OpenBankingTrackerApiProductsApiSpecs[] */
        public OpenBankingTrackerApiProductsApiSpecs[] $apiSpecs,
        public string|null $statusUrl,
        public bool $premium,
        public OpenBankingTrackerApiProductsStageEnum $stage,
        /** @var OpenBankingTrackerApiProductsCustomerTypesEnum[] */
        public OpenBankingTrackerApiProductsCustomerTypesEnum[] $customerTypes,
        /** @var mixed[] */
        public mixed[] $countries,
        public OpenBankingTrackerApiProductsApiGatewayEnum|null $apiGateway,
        /** @var OpenBankingTrackerApiProductsRateLimits[] */
        public OpenBankingTrackerApiProductsRateLimits[] $rateLimits
    )
    {
    }
}