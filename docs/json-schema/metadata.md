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

## Internals

@todo
