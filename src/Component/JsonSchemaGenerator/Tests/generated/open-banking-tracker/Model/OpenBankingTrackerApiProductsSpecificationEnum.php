<?php

declare(strict_types=1);

/*
 * This file has been auto generated by Jane,
 *
 * Do not edit it directly.
 */

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model;

enum OpenBankingTrackerApiProductsSpecificationEnum: string
{
    case OBIE_AISP = 'OBIE-AISP';
    case OBIE_CBPII = 'OBIE-CBPII';
    case OBIE_PISP = 'OBIE-PISP';
    case STET_AISP = 'STET-AISP';
    case STET_CBPII = 'STET-CBPII';
    case STET_PISP = 'STET-PISP';
    case BERLIN11 = 'BERLIN1.1';
    case BERLIN13 = 'BERLIN1.3';
}