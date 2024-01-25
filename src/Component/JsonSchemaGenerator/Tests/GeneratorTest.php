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
    public function testCompleteSpec(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/OpenBankingTracker/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\OpenBankingTracker',
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

    public function testArrayObjectNullable(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/ArrayObjectNullable/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\ArrayObjectNullable',
        ));
        $generator->fromPath(__DIR__.'/Resources/array-object-nullable.json', 'ArrayObjectNullable'); // @fixme rootModel name shouldn't be required there

        self::assertFileExists(__DIR__.'/Generated/ArrayObjectNullable/Model/Document.php');
        self::assertFileExists(__DIR__.'/Generated/ArrayObjectNullable/Model/Attributes.php');
        // @fixme generated 3 models instead of 1: Attributes, DocumentAttributes and DocumentAttributes1
        // @fixme more tests
    }

    public function testDateFormat(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/DateFormat/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\DateFormat',
            dateTimeFormat: \DateTimeInterface::ATOM, // @fixme should be used within the Normalizer but isn't right now
        ));
        $generator->fromPath(__DIR__.'/Resources/date-format.json', 'DateFormat');

        self::assertFileExists(__DIR__.'/Generated/DateFormat/Model/DateFormat.php');
        // @fixme more tests
    }

    public function testDateTimeFormat(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/DateTimeFormat/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\DateTimeFormat',
            dateTimeFormat: \DateTimeInterface::ATOM, // @fixme should be used within the Normalizer but isn't right now
        ));
        $generator->fromPath(__DIR__.'/Resources/datetime-format.json', 'DateTimeFormat');

        self::assertFileExists(__DIR__.'/Generated/DateTimeFormat/Model/DateTimeFormat.php');
        // @fixme more tests
    }

    public function testDeepObject(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/DeepObject/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\DeepObject',
        ));
        $generator->fromPath(__DIR__.'/Resources/deep-object.json', 'DeepObject');

        self::assertFileExists(__DIR__.'/Generated/DeepObject/Model/DeepObject.php');
        // @fixme more tests
    }

    public function testDefinitions(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/Definitions/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\Definitions',
        ));
        $generator->fromPath(__DIR__.'/Resources/definitions.json', 'Definitions'); // @fixme rootModel name shouldn't be required there

        self::assertFileExists(__DIR__.'/Generated/Definitions/Model/Foo.php');
        self::assertFileExists(__DIR__.'/Generated/Definitions/Model/Bar.php');
        self::assertFileExists(__DIR__.'/Generated/Definitions/Model/HelloWorld.php');
        // @fixme generated 2 models instead of 1: Bar and Bar1
        // @fixme more tests
    }

    public function testDefault(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/Default/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\Default',
        ));
        $generator->fromPath(__DIR__.'/Resources/default.json', 'Default');

        self::assertFileExists(__DIR__.'/Generated/Default/Model/_Default.php');
        self::assertFileExists(__DIR__.'/Generated/Default/Model/DefaultSubObject.php');
        // @fixme more tests
    }

    public function testDeprecated(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/Deprecated/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\Deprecated',
        ));
        $generator->fromPath(__DIR__.'/Resources/deprecated.json', 'Deprecated'); // @fixme rootModel name shouldn't be required there

        self::assertFileExists(__DIR__.'/Generated/Deprecated/Model/Deprecated.php');
        // @fixme more tests
    }

    public function testNameConflict(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/NameConflict/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\NameConflict',
        ));
        $generator->fromPath(__DIR__.'/Resources/name-conflict.json', 'NameConflict');

        self::assertFileExists(__DIR__.'/Generated/NameConflict/Model/NameConflict.php');
        // @fixme more tests
    }

    public function testNoReference(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/NoReference/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\NoReference',
        ));
        $generator->fromPath(__DIR__.'/Resources/no-reference.json', 'NoReference');

        self::assertFileExists(__DIR__.'/Generated/NoReference/Model/NoReference.php');
        // @fixme more tests
    }

    public function testNull(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/Null/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\Null',
        ));
        $generator->fromPath(__DIR__.'/Resources/null.json', 'NullModel');

        self::assertFileExists(__DIR__.'/Generated/Null/Model/NullModel.php');
        // @fixme more tests
    }

    public function testOneOf(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/OneOf/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\OneOf',
        ));
        $generator->fromPath(__DIR__.'/Resources/one-of.json', 'OneOfModel');

        self::assertFileExists(__DIR__.'/Generated/OneOf/Model/OneOfModel.php');
        // @fixme more tests


        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/OneOfNullable/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\OneOfNullable',
        ));
        $generator->fromPath(__DIR__.'/Resources/one-of-nullable.json', 'OneOfNullableModel');

        self::assertFileExists(__DIR__.'/Generated/OneOfNullable/Model/OneOfNullableModel.php');
        // @fixme more tests
    }

    public function testReadyOnly(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/ReadOnly/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\ReadOnly',
        ));
        $generator->fromPath(__DIR__.'/Resources/read-only.json', 'ReadOnlyModel');

        self::assertFileExists(__DIR__.'/Generated/ReadOnly/Model/ReadOnlyModel.php');
        // @fixme more tests
    }

    public function testReservedWords(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/ReservedWords/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\ReservedWords',
        ));
        $generator->fromPath(__DIR__.'/Resources/reserved-words.json', 'ReservedWords');

        self::assertFileExists(__DIR__.'/Generated/ReservedWords/Model/ReservedWords.php');
        // @fixme more tests
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
