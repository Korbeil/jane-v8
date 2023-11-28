<?php

declare(strict_types=1);

/*
 * This file has been auto generated by Jane,
 *
 * Do not edit it directly.
 */

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model;

enum OpenBankingTrackerApiProductsTypeEnum
{
    case accountInformation;
    case paymentInitiation;
    case paymentRequest;
    case paymentBeneficiaries;
    case paymentServiceUserIdentity;
    case realTimePayments;
    case multibanco;
    case fundsConfirmation;
    case atmLocator;
    case branchLocator;
    case productFinder;
    case invoiceFinancing;
    case availability;
    case clientRegistration;
    case cards;
    case consent;
    case auth;
    case fx;
    case other;
}
