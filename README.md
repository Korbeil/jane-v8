# jane-v8

Steps:
- Parser (Fichier/JSON > Array)
- Metadata (Json > Metadata)
- Compiler (Metadata > Représentation PHP)
- Generator (Représentation PHP > Code généré)

## Todo
Before JSON Schema ALPHA-1
- [ ] Documentation JsonSchemaGenerator
- [ ] More JsonSchemaGenerator tests
- [ ] Check all types support
  - [ ] Enum (native PHP enum)
  - [ ] Map
  - [ ] PatternMultiple

## Documentation

Jane uses [Docsify](https://docsify.js.org/) to generate the documentation. While contributing to the Jane codebase, 
it is primordial to keep the documentation up-to-date.

First you will need to install Docsify
```shell
yarn global add docsify-cli
```
Then watch the documentatation folder
```shell
docsify watch /docs
```

## Contributing

You can contribute this repository. To have an updated version of the code please checkout this repository, then:
```shell
composer update
```

Do your changes, before commit/creating a pull request, you should run:
```shell
composer cs-fix # will fix any coding style issue
composer cs-check # will check if you have any issue in your code thanks to PHPStan
composer tests # will run Jane's test suite
```

Once that is done, you can create your pull request.
Please always think about updating the [CHANGELOG](./CHANGELOG) file and add a test that is linked to the added feature
or a non-regression test if you're fixing a bug.
