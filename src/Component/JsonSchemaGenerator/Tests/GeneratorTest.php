<?php

namespace Jane\Component\JsonSchemaGenerator\Tests;

use AutoMapper\AutoMapper;
use AutoMapper\Generator\Generator as AutoMapperGenerator;
use AutoMapper\Loader\FileLoader;
use Jane\Component\JsonSchemaGenerator\Configuration;
use Jane\Component\JsonSchemaGenerator\Generator;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTracker;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Normalizer\JaneNormalizer;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;
use Jane\Component\JsonSchemaMetadata\NodeTraverser\JsonSchemaMetadataCallback;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Serializer;

class GeneratorTest extends TestCase
{
    public function testOpenBankingTracker(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/OpenBankingTracker/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\OpenBankingTracker',
            useFixer: true,
            metadataCallbacks: [new OpenBankingTrackerFixer()],
        ));
        $generator->fromPath(__DIR__.'/Resources/open-banking-tracker.json', 'OpenBankingTracker');

        self::assertFileExists(__DIR__.'/Generated/OpenBankingTracker/Model/OpenBankingTracker.php');

        $autoMapper = AutoMapper::create(loader: new FileLoader(new AutoMapperGenerator(
            (new ParserFactory())->create(ParserFactory::PREFER_PHP7),
            new ClassDiscriminatorFromClassMetadata(new ClassMetadataFactory(new AttributeLoader())),
        ), __DIR__.'/automapper-cache'));
        $serializer = new Serializer([new JaneNormalizer($autoMapper)], [new JsonEncoder()]);
        $direktBankData = file_get_contents(__DIR__.'/Resources/1822direkt-de.json');
        $creditMutuelBankData = file_get_contents(__DIR__.'/Resources/credit-mutuel.json');

        $direktBankObject = $serializer->deserialize($direktBankData, OpenBankingTracker::class, 'json');
        self::assertInstanceOf(OpenBankingTracker::class, $direktBankObject);
        $creditMutuelBankObject = $serializer->deserialize($creditMutuelBankData, OpenBankingTracker::class, 'json');
        self::assertInstanceOf(OpenBankingTracker::class, $creditMutuelBankObject);
        self::assertIsArray($creditMutuelBankObject->mobileApps);
        self::assertArrayHasKey('android', $creditMutuelBankObject->mobileApps); // checking for MapType
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
                /** @var JsonSchemaDefinition[] $properties */
                $properties = $data['items'];
                $data['properties'] = $properties;
                $data['items'] = [];

                $data['items']['required'] = $data['required'];
                $data['items']['type'] = Type::OBJECT->value;
                $data['items']['properties'] = $properties;
                unset($data['required']);
            }
        }

        return $data;
    }
}
