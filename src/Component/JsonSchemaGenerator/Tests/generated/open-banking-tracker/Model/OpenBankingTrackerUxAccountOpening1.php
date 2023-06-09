<?php

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model;

class OpenBankingTrackerUxAccountOpening1
{
    public function __construct(public bool $openAccountViaApp, public float $numberOfworkingDaysTillActiveAccount, public float $numberOfClicksToCreateAccount, public bool $instantAccessToApplePay, public bool $digitalIdVerification, public bool $asksLimitedAddressHistory, public bool $brandedWelcomeLetter)
    {
    }
}