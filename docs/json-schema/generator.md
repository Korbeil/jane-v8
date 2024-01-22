# JSON Schema Generator

This component is made to generate JSON Schema models, normalizers & validation.

## Installation

Here is the command to install this component:

```bash
composer require jane-php/json-schema-generator
```
## Usage

You can generate JSON Schema models, normalizers or validators from a file or a compiler Registry containing your JSON 
Schema:

```php
<?php
use Jane\Component\JsonSchemaGenerator\Generator;


$generator = new Generator(new Configuration(
    outputDirectory: __DIR__.'/Generated/OpenBankingTracker/',
    baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\OpenBankingTracker',
    useFixer: true,
));
$generator->fromPath(__DIR__.'/Resources/open-banking-tracker.json', 'OpenBankingTracker'); // from path
$generator->fromRegistry($compilerRegistry); // from compiler Registry
```

You will need to give the `rootModel` parameter if we have a schema defined on the root-level. Otherwise,
this is not required.

?> You can see more about manually configuring the compiler component in the [compiler documentation](json-schema/compiler.md) page.

When using the generator, you will get a collection of files (models, normalizers and/or validators depending on what
was enabled in configuration) in the given output directory.

Once everything is generated, you can freely use it within your application. For the normalizers we have some 
recommendations: When generating normalizers you will have a `JaneNormalizer` and multiple `*Normalizer` (one per 
model). The `*Normalizer` are normalizers dedicated to a single model that will handle both normalization and 
denormalization through the usage of [`jolicode/automapper`](https://github.com/jolicode/automapper). On the other hand,
`JaneNormalizer` is here to handle all normalizers in a single one, it's here to make the usage of all the normalizers
simpler, just by giving this normalizer into Symfony dependency injection you'll have all the normalizers required for 
your JSON Schema injected. 

For performance purpose, you can pass your own `AutoMapper` instance to the `JaneNormalizer`. That way you could rely 
on the file loader instead of the eval loader that will run metadata collection (reflection mostly) during all 
transformations.

Here is an example on how to inject the normalizer into Symfony:
```yaml
services:
  # ... all your services

  # simple declaration with no custom AutoMapper given
  Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Normalizer\JaneNormalizer: ~

  # more complex declaration with a custom AutoMapper
  Jane\Component\JsonSchemaGenerator\Tests\Generated\OpenBankingTracker\Normalizer\JaneNormalizer:
    arguments: ['@AutoMapper\AutoMapperInterface']
```

## Configuration

You can give some configuration to the Compiler, by doing as following
```php
<?php
use Jane\Component\JsonSchemaGenerator\Generator;
use Jane\Component\JsonSchemaGenerator\Configuration;

$configuration = new Configuration(
    outputDirectory: __DIR__.'/Generated/OpenBankingTracker/',
    baseNamespace: 'Jane\\Component\\JsonSchemaGenerator\\Tests\\Generated\\OpenBankingTracker',
    useFixer: true,
);
$compiler = new Generator($configuration);
```

- `outputDirectory`, required: The output directory of the generated files. 
- `baseNamespace`, required: Base [PSR-4](https://www.php-fig.org/psr/psr-4/) namespace for the generated files.
- `generateNormalizers`, optional: Wether to generate normalizers for generated models or not.
- `validation`, optional: Wether to generate validators for generated models or not.
- `useValidationInNormalizers`, optional: To know if we have to use validators into generated normalizers.
- `cleanGenerated`, optional: Cleaning the `outputDirectory` before generating new models, normalizers or validators.
- `useFixer`, optional: To know if we should use [`friendsofphp/php-cs-fixer`](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) onto the generated files.
- `fixedConfig`, optional: If you have a custom file to use when using [`friendsofphp/php-cs-fixer`](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer).

You can see details about configuration inherited from the `JsonSchemaCompiler` component in the
[related documentation](json-schema/compiler.md#configuration).

## Internals

### Model

The base of this component is to generate models. Generated models are fully typed and respect 
[PhpStan](https://phpstan.org/) max level. Sadly, some typing can't be done in PHP because of how the language is. In 
order to fix that we use phpDoc to describe complex types or generics. Here is an example:

```php
class OpenBankingTrackerFinancialReports
{
    public function __construct(
        public string $label,
        public string|null $date,
        /** @var OpenBankingTrackerFinancialReportsTypeEnum[]|null */
        public null|array $type,
        public string $url
    ) {
    }
}
```

This model was generated thanks to the following JSON Schema definition:

```json
{
  "type": "object",
  "required": [
    "label",
    "date",
    "url"
  ],
  "properties": {
    "label": {
      "type": "string"
    },
    "date": {
      "anyOf": [
        { "type": "string" },
        { "type": "null" }
      ]
    },
    "type": {
      "anyOf": [
        { "type": "null" },
        {
          "type": "array",
          "items": {
            "enum": [
              "analystPresentation",
              "annualReport",
              "quarterlyReport",
              "quarterlyInvestorPresentation",
              "quarterlyPressRelease",
              "annualPressRelease"
            ]
          }
        }
      ]
    },
    "url": {
      "type": "string"
    }
  }
}
```

#### Required fields

Required fields is part of the validation JSON Schema specification, but since there is case a field can be not filled,
we need to make it nullable in order to make it work in PHP since optional fields isn't something that exists in the
language.

#### AnyOf

One of the _special_ keyword within JSON Schema specification: it means the field it's in will support anything given
as a value. In the previous example we can see:

```json
{
  "anyOf": [
    { "type": "string" },
    { "type": "null" }
  ]
}
```

As a result, this field will support both `string` and `null` types. More complex types can be made with objects or even
arrays in an `anyOf`.

### Enum

While passing through all metadata to generated, we sometimes have enums type. Since PHP can handle them natively, we 
will generate them at the same time we generate models. Here is an example of a generated Enum:

```php
enum OpenBankingTrackerApiAuthEnum: string
{
    case EIDAS = 'EIDAS';
    case OAUTH2 = 'OAUTH2';
    case OPENID_CONNECT = 'OPENID-CONNECT';
    case UNKNOWN = 'UNKNOWN';
}
```

This is the result of the following JSON Schema type:

```json
{
  "enum": [
    "EIDAS",
    "OAUTH2",
    "OPENID-CONNECT",
    "UNKNOWN"
  ]
}
```

If all values are the same type and using `string` or `int` as values, we will make the enum backed by this type. 
Otherwise, the generated enum will only contain the listed cases with no backed values.

### Normalizer & JaneNormalizer

#### Normalizer

Once a model is generated, we need a Normalizer to transform a JSON input to an object or make the reverse part.
Since Jane v8, we stopped doing all the hard work of understanding the models and how to transform them as array and 
delegate that to the [AutoMapper](https://github.com/jolicode/automapper).

Now the generated Normalizer are very simple:
```php
class OpenBankingTrackerFinancialReportsNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private readonly AutoMapperInterface $autoMapper;

    public function __construct(AutoMapperInterface $autoMapper = null)
    {
        $this->autoMapper = $autoMapper ?? AutoMapper::create();
    }

    /**
     * @param OpenBankingTrackerFinancialReports $object
     *
     * @return array
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        /** @var array $output */
        $output = $this->autoMapper->map($object, 'array', $context);

        return $output;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof OpenBankingTrackerFinancialReports;
    }
    
    // ... denormalization is following
}
```

We have an optional parameter to the class. That permits us to have a custom configured `AutoMapper`. But it can totally
work _as it is_ without giving this parameter.
And then, in the `normalize` method, we use the `AutoMapper` to transform the `object` as an `array` and return it.

#### JaneNormalizer

With all the generated Normalizer, we will always generate a Normalizer called `JaneNormalizer`. It was originally 
created for performance issues to allow us to lazy-load the normalizers you need in your app without injecting all the
normalizers. And it also makes configuration way easier since you only have to inject this Normalizer in your 
application to make it work.

In this Normalizer we will list all the generated normalizers and when we try to normalize or denormalize from or to an
object, we will give the correct normalizer so the transformation can be done.

### Validator

_Not implemented yet._
