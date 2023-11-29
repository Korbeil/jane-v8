<?php

declare(strict_types=1);

/*
 * This file has been auto generated by Jane,
 *
 * Do not edit it directly.
 */

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model;

enum OpenBankingTrackerBankTypeEnum: string
{
    case UNIVERSAL = 'universal';
    case RETAIL = 'retail';
    case CORPORATE = 'corporate';
    case CHALLENGER = 'challenger';
    case HOLDING = 'holding';
    case PRIVATE = 'private';
    case INDUSTRIAL = 'industrial';
    case COMMUNITY = 'community';
    case COMMERCIAL = 'commercial';
    case DEVELOPMENT = 'development';
    case DIRECT = 'direct';
    case CREDIT_UNION = 'credit-union';
}
