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

### Guessers

While compiling your JSON Schema metadata, we will try to guess what should be, later on, generated. We can do this 
thanks to guessers. We have a very wide variety of guessers that allows us to compile metadata into something more 
understandable for the PHP ecosystem.

There is multiple guessers but here is a short presentation for each of them:
- AdditionalItems and AdditionalProperties are here when `additionalItems` or `additionalProperties` fields are filled
  to allow the presence of new items or properties based on the given definition ;
- AllOf, the `allOf` property is used to give a set of constraints that needs to all be respected, we translate that to 
  a single PHP class where all this rules are merged ;
- AnyOf, a bit like the last property but this time only at least one constraint should be respected, we can translate 
  that to a PHP union type ;
- Array, this guesser will check the type of the array items when your object if of `array` type ;
- Date, will act when you property is a `string` with the `date` format ;
- DateTime, as the DateGuesser, this one will act when format is `date-time` ;
- Enum, will handle declared `enum` to transform them as native PHP enum ;
- Multiple, will pass after SimpleType if we have more than one type to create a PHP union type ;
- Object, used to create PHP classes from JSON Schema `object` type structs ;
- OneOf, almost same as AnyOf but it can only be one constraint ;
- PatternProperties, the properties should match the given pattern and each of theses properties will have the given 
  definition ;
- SimpleType, is used to catch simple types (bool, int, float, string, null).

### Types

@todo

### Naming

Naming is pre-computed during compilation and stored within `Jane\Component\JsonSchemaCompiler\Compiled\Property` and 
`Jane\Component\JsonSchemaCompiler\Compiled\Model` objects.
The Naming service will store model names while models are compiled to ensure no duplicate are compiled. For the 
properties we store property names by model to achieve the same result within properties.

For any name we will clean it by:
- removing spaces and incorrect characters (tab, new line, carriage return, ...) ;
- replace `$` character by `dollar` word to avoid issues with how variables works in PHP ;
- remove `/`, `{`or `}` character occurrences ;
- replace accentuated character by the corresponding non-accentuated character ;
- properties or class names can't start with a number, so we add a `n` in front of the name if the first character was
  a numeric value.

And for models we will check if you are using any reserved PHP names, in that case we will had a `_` in front of the 
name.
