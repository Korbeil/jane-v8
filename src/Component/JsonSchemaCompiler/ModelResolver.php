<?php

namespace Jane\Component\JsonSchemaCompiler;

use Jane\Component\JsonSchemaCompiler\Compiled\Model;
use Jane\Component\JsonSchemaCompiler\Compiled\Property;
use Jane\Component\JsonSchemaCompiler\Compiled\Registry;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\MultipleType;
use Jane\Component\JsonSchemaCompiler\Compiled\Type\Type;
use Jane\Component\JsonSchemaCompiler\Naming\Naming;
use Jane\Component\JsonSchemaCompiler\Naming\NamingInterface;
use Jane\Component\JsonSchemaCompiler\TypeGuesser\ChainGuesser;
use Jane\Component\JsonSchemaMetadata\Metadata\JsonSchema;

class ModelResolver
{
    private readonly NamingInterface $naming;
    private readonly ChainGuesser $typeGuesser;

    public function __construct(
        NamingInterface $naming = null,
        ChainGuesser $typeGuesser = null,
        Configuration $configuration = null,
        bool $clearNaming = false,
    ) {
        $this->naming = $naming ?? new Naming(clear: $clearNaming);
        $this->typeGuesser = $typeGuesser ?? ChainGuesser::create($configuration ?? new Configuration());
    }

    public function resolve(Registry $registry, string $name, JsonSchema $schema): Model
    {
        $model = new Model($this->naming->getModelName($name));

        /**
         * @var JsonSchema $property
         */
        foreach ($schema->properties as $propertyName => $property) {
            $model->addProperty(new Property(
                name: $propertyName,
                phpName: $this->naming->getPropertyName($propertyName, $model->name),
                description: $property->description,
                type: $this->updateTypeIfNotRequired($schema, $propertyName, $this->typeGuesser->guessType($registry, $property)),
                hasDefaultValue: $property->hasDefaultValue,
                defaultValue: $property->defaultValue,
                readOnly: $property->readOnly,
                deprecated: $property->deprecated,
            ));
        }

        $registry->addModel($model);

        return $model;
    }

    /**
     * Will add a NULL type as a MultipleType if the given property is not required in the parent schema.
     */
    private function updateTypeIfNotRequired(JsonSchema $schema, string $propertyName, Type $type): Type
    {
        if (0 === \count($schema->required)) {
            return $type;
        }

        if (!\in_array($propertyName, $schema->required, true) && !$type->isNullable()) {
            if ($type instanceof MultipleType) {
                $type->types[] = new Type(Type::NULL);
            } else {
                $type = new MultipleType([$type, new Type(Type::NULL)]);
            }
        }

        return $type;
    }
}
