<?php

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model;

class OpenBankingTrackerFinancialReports
{
    public function __construct(
        public string $label,
        public string|null $date,
        /** @var string[]|null */
        public null|array $type,
        public string $url
    ) {
    }
}
