<?php

namespace Jane\Component\JsonSchemaGenerator\Tests;

use Jane\Component\JsonSchemaGenerator\Configuration;
use Jane\Component\JsonSchemaGenerator\Generator;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;
use Jane\Component\JsonSchemaMetadata\NodeTraverser\JsonSchemaMetadataCallback;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    public function testOpenBankingTracker(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/generated/open-banking-tracker/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\OpenBankingTracker',
            useFixer: true,
            metadataCallbacks: [new OpenBankingTrackerFixer()],
        ));
        $generator->fromPath(__DIR__.'/resources/open-banking-tracker.json', 'OpenBankingTracker');

        self::assertFileExists(__DIR__.'/generated/open-banking-tracker/Model/OpenBankingTracker.php');
    }
}

class OpenBankingTrackerFixer implements JsonSchemaMetadataCallback
{
    /**
     * @param JsonSchemaDefinition $data
     *
     * @return JsonSchemaDefinition
     */
    public function process(array $data): array
    {
        if (\array_key_exists('items', $data)) {
            $hasNoNumericIndex = true;
            /* @phpstan-ignore-next-line */
            foreach ($data['items'] as $k => $_) {
                if (is_numeric($k)) {
                    $hasNoNumericIndex = false;
                    break;
                }
            }
            if (\array_key_exists('type', $data)
                && ((\is_array($data['type']) && \in_array('array', $data['type'], true)) || 'array' === $data['type'])
                && \array_key_exists('required', $data)
                && \count($data['required']) > 0
                && $hasNoNumericIndex) {
                $data['type'] = Type::OBJECT->value;

                /** @var JsonSchemaDefinition[] $properties */
                $properties = $data['items'];
                $data['properties'] = $properties;
                unset($data['items']);
            }
        }

        return $data;
    }
}
