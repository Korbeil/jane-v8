# JSON Schema Metadata

This component is made to either collect JSON Schema metadata from an array to PHP objects or to create your own
JSON Schema metadata in PHP.

## Installation

Here is the command to install this component:

```bash
composer require jane-php/json-schema-metadata
```

## Usage

You can collect JSON Schema metadata from a file or from a parsed array containing your JSON Schema:

```php
<?php
use Jane\Component\JsonSchemaMetadata\Collector;

$collector = new Collector();
$registry = $collector->fromPath('/path/to/json-schema.json'); // from a file
$registry = $collector->fromParsed([
    'type' => 'string',
    'minLength' => 2,
    'maxLength' => 3
]); // from a parsed array
```

By doing this, you will get a `Registry`. It's a collection that contains all schemas that is contained within your 
JSON Schema. If you have a schema on the root-level, you can get it by doing as following:

```php
$schema = $registry->getRoot();

var_dump($schema);
//  object(Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema)#853 (42) {
//    ["type"]=>
//    array(1) {
//      [0]=>
//      enum(Jane\Component\JsonSchemaMetadata\Metadata\Type::STRING)
//    }
//    ["minLength"]=>
//    int(2)
//    ["maxLength"]=>
//    int(3)
//  }
```

## Build Metadata manually

Even if the entry point is a file or a string, you can build your JSON Schema manually if you prefer, for example:
```php
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;
use Jane\Component\JsonSchemaMetadata\Metadata\Type;
use Jane\Component\JsonSchemaMetadata\Metadata\Format;

$schema = new JsonSchema(
    title: 'Contact',
    type: Type::OBJECT,
    properties: [
        'firstName' => new JsonSchema(
            type: Type::STRING,
            minLength: 1,
        ),
        'lastName' => new JsonSchema(
            type: Type::STRING,
            minLength: 1,
        ),
        'birthday' => new JsonSchema(
            type: Type::STRING,
            format: Format::DATE,
        ),
        'emails' => new JsonSchema(
            type: Type::ARRAY,
            items: new JsonSchema(
                type: Type::STRING,
                format: Format::EMAIL,
            ),
            minItems: 1,
        ),
    ],
    required: ['firstName', 'lastName'],
);
```

## Internals

### Registry

When you get this component a parsed input, it will output a `Registry` class containing all the schemas in your input.
If you have a schema on root level it will always be stored at the `#` path, you can also recover it from 
`Registry::getRoot(): ?JsonSchema` method. 

But sometimes, your parsed input can be a collection of schemas under the `$defs` field. In this case, you will find
your schemas under the `#/$defs/<name>` path.

You can recover any schema from your JSON Schema file with the `Registry::get(string $path): ?JsonSchema` method. 
The path is kinda close to what [JSON Reference](https://json-spec.readthedocs.io/reference.html) is. For example, 
if you have a schema in `#/$defs/Contact` defining a `emails` property, you could catch its `items` schema by using 
`#/$defs/Contact/properties/emails/items` path.

### Reference

Due to how references work and the fact they could match any JSON Schema file in your folders. This library will be able
to collect metadata on references only when using the `Collector::fromPath(string $path, array $context = []): Registry` 
method.

As said, [JSON Reference](https://json-spec.readthedocs.io/reference.html) allows you to point to any schema in your file
or even in a different file. Here are some examples:
- `#/$defs/Contact`, will refer to the schema present at the `Contact` key of your definitions (`$defs` key) of your 
current schema ;
- `#/$defs/Contact/properties/emails/items`, same as before but we specificaly want to point to the emails property 
items schema ;
- `agenda/Contact.json#/$defs/Contact`, here we will refer to a `Contact.json` file that is in the `agenda` folder of
the currently collected file. And in this file we want the `Contact` definition.

### Metadata

The metadata are defined with the `Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema` class. It represents JSON 
Schema with a PHP object and can be used to build metadata programmatically.

### NodeTraverser

Node traversers are used to collect metadata from a parsed JSON Schema. There is three of them:
- `DefinitionsTraverser`: will resolve any schema found in `$defs` or `definitions` fields ; 
- `ReferenceTraverser`: any JSON reference within a schema (using `$ref` field) will be resolved and merged with the 
local schema ;
- `JsonSchemaTraverser`: is used to resolve all remaining fields within your schema.
