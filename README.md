# jane-v8

Steps:
- Parser (Fichier/JSON > Array)
- Metadata (Json > Metadata)
- Compiler (Metadata > Représentation PHP)
- Generator (Représentation PHP > Code généré)

## Todo
Before JSON Schema ALPHA-1
- [ ] Documentation JsonSchemaCompiler
- [ ] Documentation JsonSchemaGenerator
- [ ] More JsonSchemaGenerator tests
- [ ] Check all types support
  - [ ] Array
  - [ ] DateTime
  - [ ] Date
  - [ ] Dictionary
  - [ ] Enum (native PHP enum)
  - [ ] Map
  - [ ] Multiple
  - [ ] Object
  - [ ] PatternMultiple
  - [ ] Type

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
