<?php

namespace Jane\Component\JsonSchemaGenerator\Runtime;

trait AdditionalAndPatternProperties
{
    /** @var array<string, JsonSchemaDefinition>|null */
    private ?array $patternPropertiesRules = null;
    /** @var bool|JsonSchemaDefinition|null */
    private bool|array|null $additionalPropertiesRules = null;

    /** @var array<string, mixed> */
    private array $values = [];

    public function __set(string $name, mixed $value): void
    {
        if (!\array_key_exists($name, $this->values) && false === $this->isValidProperty($name)) {
            throw new \RuntimeException('Invalid property');
        }

        $this->values[$name] = $value;
    }

    public function __get(string $name): mixed
    {
        if (\array_key_exists($name, $this->values)) {
            return $this->values[$name];
        }

        $matchedPattern = $this->isValidProperty($name);

        if (\is_string($matchedPattern) && null !== $this->patternPropertiesRules && \array_key_exists('default', $this->patternPropertiesRules[$matchedPattern])) {
            $this->values[$name] = $this->patternPropertiesRules[$matchedPattern]['default'];

            return $this->values[$name];
        }

        if (true === $matchedPattern && null !== $this->additionalPropertiesRules && !\is_bool($this->additionalPropertiesRules) && \array_key_exists('default', $this->additionalPropertiesRules)) {
            $this->values[$name] = $this->additionalPropertiesRules['default'];

            return $this->values[$name];
        }

        throw new \RuntimeException('Invalid property or non-initialized property');
    }

    private function isValidProperty(string $property): bool|string
    {
        if ($this instanceof PatternPropertiesInterface) {
            if (null === $this->patternPropertiesRules) {
                /** @var array<string, JsonSchemaDefinition> $rules */
                $rules = json_decode(static::PATTERN_PROPERTIES_RULES, true); // @phpstan-ignore-line
                $this->patternPropertiesRules = $rules;
            }

            foreach (array_keys($this->patternPropertiesRules) as $pattern) {
                if (false !== ($result = preg_match(sprintf('#%s#', $pattern), $property)) && $result > 0) {
                    return $pattern;
                }
            }
        }

        if ($this instanceof AdditionalPropertiesInterface) {
            if (null === $this->additionalPropertiesRules) {
                /** @var bool|JsonSchemaDefinition $rules */
                $rules = json_decode(static::ADDITIONAL_PROPERTIES_RULES, true); // @phpstan-ignore-line
                $this->additionalPropertiesRules = $rules;
            }

            if (true === $this->additionalPropertiesRules || \is_array($this->additionalPropertiesRules)) {
                return true;
            }
        }

        return false;
    }
}
