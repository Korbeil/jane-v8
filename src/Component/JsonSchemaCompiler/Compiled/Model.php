<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled;

class Model
{
    public readonly string $modelName;
    public readonly string $normalizerName;

    public function __construct(
        public readonly string $name,
        /** @var Property[] $properties */
        public array $properties = [],
    ) {
        $this->modelName = $name;
        $this->normalizerName = sprintf('%sNormalizer', $name);
    }

    public function addProperty(Property $property): void
    {
        $this->properties[] = $property;
    }

    public function getProperty(string $propertyName): ?Property
    {
        foreach ($this->properties as $property) {
            if ($propertyName === $property->name) {
                return $property;
            }
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function getPropertyNames(): array
    {
        return array_map(function (Property $property) {
            return $property->name;
        }, $this->properties);
    }
}
