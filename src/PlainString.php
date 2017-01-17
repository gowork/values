<?php

namespace GW\Value;

final class PlainString implements StringValue
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function lastPosition(string $needle): ?int
    {
        return ($pos = strrpos($this->value, $needle)) !== false ? $pos : null;
    }

    public function stripTags(): StringValue
    {
        return new self(strip_tags($this->value));
    }

    public function trim(string $characterMask = " \t\n\r\0\x0B"): StringValue
    {
        return new self(trim($this->value, $characterMask));
    }

    public function trimRight(string $characterMask = " \t\n\r\0\x0B"): StringValue
    {
        return new self(rtrim($this->value, $characterMask));
    }

    public function trimLeft(string $characterMask = " \t\n\r\0\x0B"): StringValue
    {
        return new self(ltrim($this->value, $characterMask));
    }

    public function lower(): StringValue
    {
        return new self(mb_strtolower($this->value));
    }

    public function upper(): StringValue
    {
        return new self(mb_strtoupper($this->value));
    }

    public function lowerFirst(): StringValue
    {
        return new self(lcfirst($this->value));
    }

    public function upperFirst(): StringValue
    {
        return new self(ucfirst($this->value));
    }

    public function upperWords(): StringValue
    {
        return new self(ucwords($this->value));
    }

    public function padRight(int $length, string $string = ' '): StringValue
    {
        return new self(str_pad($this->value, $length, $string, STR_PAD_RIGHT));
    }

    public function padLeft(int $length, string $string = ' '): StringValue
    {
        return new self(str_pad($this->value, $length, $string, STR_PAD_LEFT));
    }

    public function padBoth(int $length, string $string = ' '): StringValue
    {
        return new self(str_pad($this->value, $length, $string, STR_PAD_BOTH));
    }

    public function replace(string $search, string $replace): StringValue
    {
        return new self(str_replace($search, $replace, $this->value));
    }

    public function translate(array $pairs): StringValue
    {
        return new self(strtr($this->value, $pairs));
    }

    public function replacePattern(string $pattern, string $replacement): StringValue
    {
        return new self(preg_replace($pattern, $replacement, $this->value));
    }

    public function replacePatternCallback(string $pattern, callable $callback): StringValue
    {
        return new self(preg_replace_callback($pattern, $callback, $this->value));
    }

    public function matchPatterns(string $pattern): ArrayValue
    {
        preg_match($pattern, $this->value, $matches);

        return Arrays::create($matches);
    }

    public function isMatching(string $pattern): bool
    {
        return $this->matchAllPatterns($pattern)->count() > 0;
    }

    public function matchAllPatterns(string $pattern): ArrayValue
    {
        preg_match_all($pattern, $this->value, $matches, PREG_SET_ORDER);

        return Arrays::create($matches);
    }

    public function splitByPattern(string $pattern): ArrayValue
    {
        return Arrays::create(preg_split($pattern, $this->value));
    }

    public function explode(string $delimiter): ArrayValue
    {
        return Arrays::create(explode($delimiter, $this->value));
    }

    public function truncate(int $length, string $postfix = '...'): StringValue
    {
        if ($this->length() <= $length) {
            return $this;
        }

        return new self(mb_substr($this->value, 0, $length) . $postfix);
    }

    public function length(): int
    {
        return mb_strlen($this->value);
    }

    public function contains(string $substring): bool
    {
        return $this->position($substring) !== null;
    }

    public function position(string $needle): ?int
    {
        return ($pos = strpos($this->value, $needle)) !== false ? $pos : null;
    }

    public function toString(): string
    {
        return $this->value();
    }

    public function value(): string
    {
        return $this->value;
    }
}
