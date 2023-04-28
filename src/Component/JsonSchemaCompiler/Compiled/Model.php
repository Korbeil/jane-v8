<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled;

class Model
{
    public function __construct(
        public string $name,
        /** @var Property[] $properties */
        public array $properties = [],
    ) {
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
