<?php

namespace Jane\Component\JsonSchemaMetadata\Metadata;

enum Format: string
{
    case DATE_TIME = 'date-time';
    case DATE = 'date';
    case TIME = 'time';
    case DURATION = 'duration';

    case EMAIL = 'email';
    case IDN_EMAIL = 'idn-email';

    case HOSTNAME = 'hostname';
    case IDN_HOSTNAME = 'idn-hostname';

    case IPV4 = 'ipv4';
    case IPV6 = 'ipv6';

    case URI = 'uri';
    case URI_REFERENCE = 'uri-reference';
    case URI_TEMPLATE = 'uri-template';
    case IRI = 'iri';
    case IRI_REFERENCE = 'iri-reference';

    case UUID = 'uuid';
    case REGEX = 'regex';

    public static function fromName(string $name): self
    {
        foreach (self::cases() as $status) {
            if ($name === $status->value) {
                return $status;
            }
        }

        throw new \ValueError("\"$name\" is not a valid backing value for enum ".self::class);
    }
}
