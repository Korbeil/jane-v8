<?php

namespace Jane\Component\JsonSchemaCompiler\Compiled;

class Model
{
    public function __construct(
        public string $name,
        /** @var ModelProperty[] $properties */
        public array $properties = [],
    ) {
    }

    public function addProperty(ModelProperty $property): void
    {
        $this->properties[] = $property;
    }

    /**
     * @return string[]
     */
    public function getPropertyNames(): array
    {
        return array_map(function (ModelProperty $property) {
            return $property->name;
        }, $this->properties);
    }
}
