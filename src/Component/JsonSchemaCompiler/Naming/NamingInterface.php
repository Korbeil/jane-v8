<?php

namespace Jane\Component\JsonSchemaCompiler\Naming;

interface NamingInterface
{
    /**
     * @param string $name      Model name you want to use
     * @param int    $iteration Iteration of the name you passed, used when we have duplicates
     *
     * @return string Cleaned name for a PHP model
     */
    public function getModelName(string $name, int $iteration = 0): string;

    /***
     * @param string $name Property name you want to use
     * @param string|null $model Model where your property will be used, by passing this, it will de-duplicate property names
     *
     * @return string Cleaned name for a PHP property
     */
    public function getPropertyName(string $name, string $model = null): string;

    /**
     * @param string $name      Enum name you want to use
     * @param int    $iteration Iteration of the name you passed, used when we have duplicates
     *
     * @return string Cleaned name for a PHP enum
     */
    public function getEnumName(string $name, int $iteration = 0): string;

    /**
     * @param int|float|string $name $name Enum case name you want to use
     *
     * @return string Cleaned name for a PHP Enum case
     */
    public function getEnumCaseName($name): string;
}
