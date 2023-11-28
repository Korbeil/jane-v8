<?php

namespace Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model;

enum OpenBankingTrackerApiAuthEnum
{
    case EIDAS;
    case OAUTH2;
    case OPENID-CONNECT;
    case UNKNOWN;
}