# JSON Schema quick start

This component is designed to simplify the generation of models, normalizers, and validators based on JSON Schema
specifications.

We'll take the following schema as the example schema:
```json
{
  "$id": "https://example.com/person.schema.json",
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "title": "Person",
  "type": "object",
  "properties": {
    "firstName": {
      "type": "string",
      "description": "The person's first name."
    },
    "lastName": {
      "type": "string",
      "description": "The person's last name."
    },
    "age": {
      "description": "Age in years which must be equal to or greater than zero.",
      "type": "integer",
      "minimum": 0
    }
  }
}
```

To generate a model for this schema we'll install the JSON Schema Generator component by doing as following:
```shell
composer require --dev jane-php/json-schema-generator
```
We install it as a dev dependency since we only need it to generate files then we won't need it during runtime.

Once installed, we need to make a script to run the generator, I usually create this script in a directory called `bin`.
So here is a quick example of my `bin/generate-models` file:
```php
#!/usr/bin/php
<?php

$generator = new Generator(new Configuration(
    outputDirectory: __DIR__.'/../generated/Person/',
    baseNamespace: 'App\\Generated\\Person',
    useFixer: true,
));
$generator->fromPath(__DIR__.'/../schemas/person.json', 'Person');
```
The `outputDirectory` is where the generated files will be put, `baseNamespace` is the namespace to use for the given
output directory and `useFixer` is here to tell the generator if we want our code to be cleaned or not by 
[PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer).

?> You can find more details about the JSON Schema generator configuration in 
[the dedicated documentation](json-schema/generator.md#configuration).

You can now run your script, once done you'll have something like that:
```shell
 $ tree
.
├── bin
│   └── generate-models
├── generated
│   └── Person
│       ├── Model
│       │   └── Person.php
│       └── Normalizer
│           ├── JaneNormalizer.php
│           └── PersonNormalizer.php
├── schemas
│   └── person.json
└── src # your application ...
```

?> Generated model can be [found there](https://github.com/Korbeil/jane-v8/blob/main/src/Component/JsonSchemaGenerator/Tests/Generated/Person/Model/Person.php)

We recommend to commit the generated code and to have it in a separated folder from your source folder. In order to make
it work, you'll need to add a line for auto-loading in you `composer.json`:
```json
{
  "autoload": {
    "psr-4": {
      "App\\Generated\\": "generated/"
    }
  }
}
```

And with all that you're ready to use JSON Schema models !

?> You can find more details about the JSON Schema generator in [the dedicated documentation](json-schema/generator.md).
