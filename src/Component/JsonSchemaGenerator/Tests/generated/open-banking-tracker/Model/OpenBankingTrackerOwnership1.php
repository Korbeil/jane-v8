<?php

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model;

class OpenBankingTrackerOwnership1
{
    public function __construct(public string|null $shareholderName, public string|null $shareholderIconUrl, public string|null $providerId, public float|null $percentage, public string $sourceUrl)
    {
    }
}