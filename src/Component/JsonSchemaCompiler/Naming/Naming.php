<?php

namespace Jane\Component\JsonSchemaCompiler\Naming;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Jane\Component\JsonSchemaCompiler\Compiled\Model;

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

    public const ACCENTED_CHARACTERS = ['Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A',
        'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E',
        'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
        'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B',
        'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o',
        'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u',
        'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', 'Ğ' => 'G', 'İ' => 'I', 'Ş' => 'S', 'ğ' => 'g', 'ı' => 'i',
        'ş' => 's', 'ü' => 'u',
    ];

    private readonly Inflector $inflector;

    public function __construct()
    {
        $this->inflector = InflectorFactory::create()->build();
    }

    public function getModelName(string $name): string
    {
        $name = $this->cleaning($name, true);

        $regexResult = preg_match(self::BAD_CLASS_NAME_REGEX, $name);
        if (false !== $regexResult && $regexResult > 0) {
            $name = '_'.$name;
        }

        return $name;
    }

    public function getPropertyName(string $name, Model $model = null): string
    {
        $name = $this->cleaning($name);
        // php property can't start with a number
        if (is_numeric(substr($name, 0, 1))) {
            $name = 'n'.$name;
        }

        if (null !== $model && \in_array($name, $model->getPropertyNames(), true)) {
            $index = 0;
            $baseName = $name;
            do {
                ++$index;
                $name = $baseName.$index;
            } while (\in_array($name, $model->getPropertyNames(), true));
        }

        return $name;
    }

    private function cleaning(string $name, bool $model = false): string
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
        $name = str_replace(array_keys(self::ACCENTED_CHARACTERS), array_values(self::ACCENTED_CHARACTERS), $name);

        // Doctrine Inflector does not seem to handle some characters (like dots, @, :) well.
        // So replace invalid char by an underscore to allow Doctrine to uppercase word correctly.
        /** @var string $name */
        $name = preg_replace('/[^a-z0-9 ]+/iu', '_', $name);

        if ($model) {
            return $this->inflector->classify($name);
        }

        return $this->inflector->camelize($name);
    }
}
