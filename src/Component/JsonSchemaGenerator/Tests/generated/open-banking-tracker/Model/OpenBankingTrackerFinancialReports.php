<?php

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model;

class OpenBankingTrackerFinancialReports
{
    public function __construct(
        public string $label,
        public string|null $date,
        /** @var null|OpenBankingTrackerFinancialReportsTypeEnum[] */
        public null|OpenBankingTrackerFinancialReportsTypeEnum[] $type,
        public string $url
    )
    {
    }
}