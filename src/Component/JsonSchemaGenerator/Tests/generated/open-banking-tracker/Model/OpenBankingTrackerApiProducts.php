<?php

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model;

class OpenBankingTrackerApiProducts
{
    public function __construct(
        public string $label,
        public string $type,
        /** @var string[] */
        public array $categories,
        public string $regulation,
        public string $specification,
        public string|null $description,
        public string|null $documentationUrl,
        public string|null $apiReferenceUrl,
        /** @var OpenBankingTrackerApiProductsApiSpecs[] */
        public array $apiSpecs,
        public string|null $statusUrl,
        public bool $premium,
        public string $stage,
        /** @var string[] */
        public array $customerTypes,
        /** @var mixed[] */
        public array $countries,
        public string|null $apiGateway,
        /** @var OpenBankingTrackerApiProductsRateLimits[] */
        public array $rateLimits
    ) {
    }
}
