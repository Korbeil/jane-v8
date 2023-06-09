<?php

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model;

class OpenBankingTrackerCompliance1
{
    public function __construct(public string $regulation, public string $status, public string $sourceUrl, public bool $fallbackExemption)
    {
    }
}