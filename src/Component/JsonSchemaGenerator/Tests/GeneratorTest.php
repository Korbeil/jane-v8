<?php

namespace Jane\Component\JsonSchemaGenerator\Tests;

use AutoMapper\AutoMapper;
use AutoMapper\Generator\MapperGenerator as AutoMapperGenerator;
use AutoMapper\Generator\Shared\ClassDiscriminatorResolver;
use AutoMapper\Loader\FileLoader;
use Jane\Component\JsonSchemaGenerator\Configuration;
use Jane\Component\JsonSchemaGenerator\Generator;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\Default\Model\_Default;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\Default\Model\DefaultSubObject;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\Deprecated\Model\Foo as Deprecated;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\NameConflict\Model\NameConflict;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\NoReference\Model\NoReference;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\NoReference\Model\NoReferenceSubObject;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\Null\Model\NullModel;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Model\OpenBankingTracker;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Normalizer\JaneNormalizer;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\ReadOnly\Model\Foo as ReadOnlyModel;
use Jane\Component\JsonSchemaGenerator\Tests\Generated\ReservedWords\Model\_List as ReservedWords;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;
use Jane\Component\JsonSchemaMetadata\NodeTraverser\JsonSchemaMetadataCallback;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Serializer;

class GeneratorTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $filesystem = new Filesystem();
        // comment the following line if you want to have generate models when making tests
        $filesystem->remove(Path::normalize(__DIR__.'/Generated'));
    }

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
            new ClassDiscriminatorResolver(new ClassDiscriminatorFromClassMetadata(new ClassMetadataFactory(new AttributeLoader()))),
            true,
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
        $generator->fromPath(__DIR__.'/Resources/array-object-nullable.json');

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
        $generator->fromPath(__DIR__.'/Resources/definitions.json');

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

        $class = new \ReflectionClass(_Default::class);
        self::assertCount(7, $properties = $class->getProperties());
        self::assertEquals('subObject', $properties[0]->name);
        self::assertEquals(DefaultSubObject::class, $properties[0]->getType()->getName());
        self::assertEquals('string', $properties[1]->name);
        self::assertEquals('string', $properties[1]->getType()->getName());
        self::assertEquals('bool', $properties[2]->name);
        self::assertEquals('bool', $properties[2]->getType()->getName());
        self::assertEquals('integer', $properties[3]->name);
        self::assertEquals('int', $properties[3]->getType()->getName());
        self::assertEquals('float', $properties[4]->name);
        self::assertEquals('float', $properties[4]->getType()->getName());
        self::assertEquals('array', $properties[5]->name);
        self::assertEquals('array', $properties[5]->getType()->getName());
        self::assertEquals('object', $properties[6]->name);
        self::assertEquals('array', $properties[6]->getType()->getName());

        $constructMethod = $class->getMethod('__construct');
        // we need to check default values onto method parameters instead of class parameters
        // check: https://github.com/php/php-src/issues/13250 for more details
        self::assertCount(7, $properties = $constructMethod->getParameters());
        self::assertFalse($properties[0]->isDefaultValueAvailable());
        self::assertEquals('content', $properties[1]->getDefaultValue());
        self::assertTrue($properties[2]->getDefaultValue());
        self::assertEquals(10, $properties[3]->getDefaultValue());
        self::assertEquals(3.4, $properties[4]->getDefaultValue());
        self::assertEquals(['value'], $properties[5]->getDefaultValue());
        self::assertEquals(['key' => 'value'], $properties[6]->getDefaultValue());

        $class = new \ReflectionClass(DefaultSubObject::class);
        self::assertCount(1, $properties = $class->getProperties());
        self::assertEquals('foo', $properties[0]->name);
        self::assertEquals('string', $properties[0]->getType()->getName());

        $constructMethod = $class->getMethod('__construct');
        self::assertCount(1, $properties = $constructMethod->getParameters());
        self::assertEquals('subContent', $properties[0]->getDefaultValue());
    }

    public function testDeprecated(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/Deprecated/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\Deprecated',
        ));
        $generator->fromPath(__DIR__.'/Resources/deprecated.json');

        self::assertFileExists(__DIR__.'/Generated/Deprecated/Model/Foo.php');

        $class = new \ReflectionClass(Deprecated::class);
        $properties = $class->getProperties();

        self::assertEquals('foo', $properties[1]->name);
        self::assertStringContainsString('@deprecated', \is_string($properties[1]->getDocComment()) ? $properties[1]->getDocComment() : '');
    }

    public function testNameConflict(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/NameConflict/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\NameConflict',
        ));
        $generator->fromPath(__DIR__.'/Resources/name-conflict.json', 'NameConflict');

        self::assertFileExists(__DIR__.'/Generated/NameConflict/Model/NameConflict.php');
        $class = new \ReflectionClass(NameConflict::class);
        self::assertCount(3, $properties = $class->getProperties());
        self::assertNotEquals($properties[0], $properties[1]);
        self::assertNotEquals($properties[1], $properties[2]);
        self::assertNotEquals($properties[0], $properties[2]);
    }

    public function testNoReference(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/NoReference/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\NoReference',
        ));
        $generator->fromPath(__DIR__.'/Resources/no-reference.json', 'NoReference');

        self::assertFileExists(__DIR__.'/Generated/NoReference/Model/NoReference.php');
        self::assertFileExists(__DIR__.'/Generated/NoReference/Model/NoReferenceSubObject.php');
        self::assertIsObject(new NoReference('string', new NoReferenceSubObject('foo')));
    }

    public function testNull(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/Null/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\Null',
        ));
        $generator->fromPath(__DIR__.'/Resources/null.json', 'NullModel');

        self::assertFileExists(__DIR__.'/Generated/Null/Model/NullModel.php');

        $class = new \ReflectionClass(NullModel::class);
        self::assertCount(4, $properties = $class->getProperties());
        self::assertEquals('onlyNull', $properties[0]->name);
        self::assertEquals('null', $properties[0]->getType()->getName());
        self::assertEquals('nullOrString', $properties[1]->name);
        self::assertEquals('string', $properties[1]->getType()->getName());
        self::assertTrue($properties[1]->getType()?->allowsNull());
        self::assertEquals('required', $properties[2]->name);
        self::assertEquals('string', $properties[2]->getType()->getName());
        self::assertEquals('requiredNull', $properties[3]->name);
        self::assertEquals('string', $properties[3]->getType()->getName());
        self::assertTrue($properties[3]->getType()?->allowsNull());
    }

    public function testOneOf(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/OneOf/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\OneOf',
        ));
        $generator->fromPath(__DIR__.'/Resources/one-of.json');

        self::assertFileExists(__DIR__.'/Generated/OneOf/Model/Foo.php');
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
        $generator->fromPath(__DIR__.'/Resources/read-only.json');

        self::assertFileExists(__DIR__.'/Generated/ReadOnly/Model/Foo.php');

        $class = new \ReflectionClass(ReadOnlyModel::class);
        $properties = $class->getProperties();

        self::assertCount(3, $properties);
        self::assertEquals('foo', $properties[0]->name);
        self::assertTrue($properties[0]->isReadOnly());
    }

    public function testReservedWords(): void
    {
        $generator = new Generator(new Configuration(
            outputDirectory: __DIR__.'/Generated/ReservedWords/',
            baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\ReservedWords',
        ));
        $generator->fromPath(__DIR__.'/Resources/reserved-words.json');

        self::assertFileExists(__DIR__.'/Generated/ReservedWords/Model/_List.php');
        self::assertEquals('Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\ReservedWords\\Model\\_List', ReservedWords::class);
        $class = new \ReflectionClass(ReservedWords::class);
        $properties = $class->getProperties();
        self::assertEquals('array', $properties[0]->name);
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
