<?php

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model;

class OpenBankingTrackerFinancialReports1
{
    public function __construct(
        public string $label,
        public string|null $date,
        /** @var null|string[] */
        public null|array $type,
        public string $url
    )
    {
    }
}