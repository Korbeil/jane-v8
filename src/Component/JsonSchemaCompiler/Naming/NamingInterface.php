<?php

namespace Jane\Component\JsonSchemaCompiler\Naming;

use Jane\Component\JsonSchemaCompiler\Compiled\Model;

interface NamingInterface
{
    /**
     * @param string $name Model name you want to use
     *
     * @return string Cleaned name for a PHP model
     */
    public function getModelName(string $name): string;

    /***
     * @param string $name Property name you want to use
     * @param Model|null $model Model where your property will be used, by passing this, it will de-duplicate property names
     *
     * @return string Cleaned name for a PHP property
     */
    public function getPropertyName(string $name, Model $model = null): string;
}
