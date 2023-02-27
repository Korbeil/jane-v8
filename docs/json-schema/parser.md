# JSON Schema Parser

This component is designed to take a raw JSON file (or string) and output it as an array.
It could be compared as [an Encoder](https://symfony.com/doc/current/components/serializer.html#encoders ':target=_blank') 
as its described the Symfony Serializer.

## Installation

Here is the command to install this component:

```bash
composer require jane-php/json-schema-parser
```

## Usage

You can either parse from a file or from a raw string:

```php
<?php
use Jane\Component\JsonSchemaParser\Parser;

$parser = new Parser();
$parsed = $parser->fromPath('/path/to/json-schema.json'); // from a file
$parsed = $parser->fromString(<<<JSON
{
  "type": "string",
  "minLength": 2,
  "maxLength": 3
}
JSON); // from raw JSON string

var_dump($parsed);
//  array(3) {
//    ["type"]=>
//    string(6) "string"
//    ["minLength"]=>
//    int(2)
//    ["maxLength"]=>
//    int(3)
//  }
```

Both will output an array that describe your JSON Schema.

## Internals

This component is really straight forward, we either load the contents of a file or take a raw string (`fromPath` will
call `fromString` internally so the result will always be the same) and we use the 
[Symfony's JSON encoder](https://github.com/symfony/symfony/blob/6.3/src/Symfony/Component/Serializer/Encoder/JsonEncoder.php) 
on that string to output a parsed array.
