<?php

declare(strict_types=1);

/*
 * This file has been auto generated by Jane,
 *
 * Do not edit it directly.
 */

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model;

enum OpenBankingTrackerApiProductsStageEnum: string
{
    case LIVE = 'live';
    case PRODUCTION = 'production';
    case SANDBOX = 'sandbox';
    case PRIVATEBETA = 'privateBeta';
    case UPCOMING = 'upcoming';
    case PROTOTYPE = 'prototype';
    case IDEA = 'idea';
    case UNKNOWN = 'unknown';
    case DEPRECATED = 'deprecated';
}
