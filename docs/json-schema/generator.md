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

### Enum

### Normalizer & JaneNormalizer

### Validator

@fixme
