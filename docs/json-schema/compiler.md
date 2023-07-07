# JSON Schema Compiler

This component is made to re-shape JSON Schema metadata in order to fit to PHP structures.

## Installation

Here is the command to install this component:

```bash
composer require jane-php/json-schema-compiler
```

## Usage

You can compile JSON Schema metadata from a file or a metadata Registry containing your JSON Schema:

```php
<?php
use Jane\Component\JsonSchemaCompiler\Compiler;

$compiler = new Compiler();
$registry = $compiler->fromPath('open-banking-tracker.json', rootModel: 'OpenBankingTracker'); // from path
$registry = $compiler->fromMetadata($metadataRegistry, rootModel: 'OpenBankingTracker'); // from metadata Registry
```

You will need to give the `rootModel` parameter if we have a schema defined on the root-level. Otherwise,
this is not required.

?> You can see more about manually metadata building in the [metadata component](json-schema/metadata.md) page.

Then by compiling your JSON Schema, you will get a `Registry`. It's a collection that contains all models that is 
contained within your specification.
Once you get that `Registry`, you can get a model by using the `getModel` method:

```php
$model = $registry->getModel('OpenBankingTracker');
var_dump($model);
//  object(Jane\Component\JsonSchemaCompiler\Compiled\Model)#4162 (2) {
//    ["name"]=>
//    string(18) "OpenBankingTracker"
//    ["properties"]=>
//    array(71) {
//      [0]=>
//      object(Jane\Component\JsonSchemaCompiler\Compiled\Property)#4163 (8) {
//        ["name"]=>
//        string(2) "id"
//        ["phpName"]=>
//        string(3) "id1"
//        ["description"]=>
//        string(18) "Unique indentifier"
//        ["type"]=>
//        object(Jane\Component\JsonSchemaCompiler\Compiled\Type\Type)#4164 (1) {
//          ["type"]=>
//          string(6) "string"
//        }
//        ["hasDefaultValue"]=>
//        bool(false)
//        ["defaultValue"]=>
//        NULL
//        ["readOnly"]=>
//        bool(false)
//        ["deprecated"]=>
//        bool(false)
//      }
//      ...
//    }
//  }
```

## Configuration

You can give some configuration to the Compiler, by doing as following
```php
<?php
use Jane\Component\JsonSchemaCompiler\Compiler;
use Jane\Component\JsonSchemaCompiler\Configuration;

$configuration = new Configuration(
    dateFormat: 'Y-m-d',
    dateTimeFormat: \DateTimeInterface::ATOM,
    dateUsedClass: \DateTime::class,
    dateTypedClass: \DateTime::class,
);
$compiler = new Compiler($configuration);
```

- `dateFormat`: Used to define how to format a `date` formatted string. By default, it's `Y-m-d`, see 
  [PHP documentation](https://www.php.net/manual/fr/datetime.format.php#refsect1-datetime.format-parameters) for more
  details about formatting dates.
- `dateTimeFormat`: Used to define how to format a `date-time` formatted string. By default, it's `Y-m-d\TH:i:sP`, see
  [PHP documentation](https://www.php.net/manual/fr/datetime.format.php#refsect1-datetime.format-parameters) for more
  details about formatting dates.
- `dateUsedClass`: Used to choose which PHP class we will instantiate when denormalizing a `date` formatted string. By 
  default, we use `\DateTime`.
- `dateTypedClass`: Used to choose which PHP class we will instantiate when denormalizing a `date-time` formatted 
  string. By default, we use `\DateTime`.

You can see details about configuration inherited from the `JsonSchemaMetadata` component in the 
[related documentation](json-schema/metadata.md#configuration).

## Internals

@todo
### Naming

@todo
