<?php

namespace Jane\Component\JsonSchemaCompiler\Naming;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;

class Naming implements NamingInterface
{
    public const BAD_CLASS_NAME_REGEX = '/^
        ([0-9])|
        \b(
            (a(bstract|nd|rray|s))|
            (c(a(llable|se|tch)|l(ass|one)|on(st|tinue)))|
            (d(e(clare|fault)|ie|o))|
            (e(cho|lse(if)?|mpty|nd(declare|for(each)?|if|switch|while)|val|x(it|tends)))|
            (f(inal|or(each)?|unction))|
            (g(lobal|oto))|
            (i(f|mplements|n(clude(_once)?|st(anceof|eadof)|terface)|sset))|
            (n(amespace|ew))|
            (p(r(i(nt|vate)|otected)|ublic))|
            (re(quire(_once)?|turn))|
            (s(tatic|witch))|
            (t(hrow|r(ait|y)))|
            (u(nset|se))|
            (__halt_compiler|break|list|(x)?or|var|while)
        )\b
    /ix';

    private readonly Inflector $inflector;

    /** @var string[] */
    private static array $classNames = [];
    /** @var array<string, string[]> */
    private static array $propertyNames = [];

    public function __construct(bool $clear = false)
    {
        $this->inflector = InflectorFactory::create()->build();
        if ($clear) {
            self::$classNames = self::$propertyNames = [];
        }
    }

    public function clear(): void
    {
        self::$classNames = self::$propertyNames = [];
    }

    public function getPropertyName(string $name, string $model = null): string
    {
        $name = $this->cleaning($name);

        if (null !== $model) {
            if (!\array_key_exists($model, self::$propertyNames)) {
                self::$propertyNames[$model] = [];
            }

            if (\in_array($name, self::$propertyNames[$model], true)) {
                $index = 0;
                $baseName = $name;
                do {
                    ++$index;
                    $name = $baseName.$index;
                } while (\in_array($name, self::$propertyNames[$model], true));
            }

            self::$propertyNames[$model][] = $name;
        }

        return $name;
    }

    public function getModelName(string $name, int $iteration = 0): string
    {
        return $this->getClassName($name, $iteration);
    }

    public function getEnumName(string $name, int $iteration = 0): string
    {
        return $this->getClassName($name, $iteration, 'Enum');
    }

    /**
     * @param int|float|string $name
     */
    public function getEnumCaseName($name): string
    {
        if (\is_int($name) || \is_float($name)) {
            $name = 'VALUE'.(string) $name;

            return str_replace('.', '_', $name);
        }

        return $this->cleaning($name, false, true);
    }

    private function cleaning(string $name, bool $model = false, bool $constant = false): string
    {
        $name = trim($name); // clean spaces

        $regexResult = preg_match('/\$/', $name);
        if (false !== $regexResult && $regexResult > 0) {
            /** @var string $name */
            $name = preg_replace_callback('/\$([a-z])/', function ($matches) {
                return 'dollar'.ucfirst($matches[1]);
            }, $name);
        }

        /** @var string $name */
        $name = preg_replace_callback('#[/\{\}]+(\w)#', function ($matches) {
            return ucfirst($matches[1]);
        }, $name);

        // replace accented characters
        /** @var string $name */
        $name = $this->inflector->unaccent($name);

        // Doctrine Inflector does not seem to handle some characters (like dots, @, :) well.
        // So replace invalid char by an underscore to allow Doctrine to uppercase word correctly.
        /** @var string $name */
        $name = preg_replace('/[^a-z0-9 ]+/iu', '_', $name);

        // php property can't start with a number
        if (is_numeric(substr($name, 0, 1))) {
            $name = 'n'.$name;
        }

        if ($model) {
            return $this->inflector->classify($name);
        }

        if ($constant) {
            // Transform all uppercase words to camel case
            $name = preg_replace_callback('/([A-Z])([A-Z]+)/', function ($matches) {
                return $matches[1].strtolower($matches[2]);
            }, $name);
            // We needs those two steps because tableizer alone
            // would keep spaces between words
            $name = $this->inflector->camelize((string) $name);
            $name = $this->inflector->tableize($name);

            return mb_strtoupper($name);
        }

        return $this->inflector->camelize($name);
    }

    private function getClassName(string $name, int $iteration, string $suffix = null): string
    {
        $baseName = $name;
        $name = $this->cleaning($name, true);

        $regexResult = preg_match(self::BAD_CLASS_NAME_REGEX, $name);
        if (false !== $regexResult && $regexResult > 0) {
            $name = '_'.$name;
        }

        if ($iteration > 0) {
            $name .= $iteration;
        }
        if (null !== $suffix) {
            $name .= $suffix;
        }

        if (\in_array($name, self::$classNames, true)) {
            return $this->getClassName($baseName, $iteration + 1, $suffix);
        }

        self::$classNames[] = $name;

        return $name;
    }
}
