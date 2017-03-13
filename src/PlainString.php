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

    public function substring(int $start, ?int $length = null): PlainString
    {
        return new self(mb_substr($this->value, $start, $length));
    }

    public function postfix(StringValue $other): PlainString
    {
        return new self($this->value . $other->toString());
    }

    public function prefix(StringValue $other): PlainString
    {
        return new self($other->toString() . $this->value);
    }

    public function transform(callable $transformer): PlainString
    {
        return new self($transformer($this->value));
    }

    public function stripTags(): PlainString
    {
        return new self(strip_tags($this->value));
    }

    public function trim(string $characterMask = self::TRIM_MASK): PlainString
    {
        return new self(trim($this->value, $characterMask));
    }

    public function trimRight(string $characterMask = self::TRIM_MASK): PlainString
    {
        return new self(rtrim($this->value, $characterMask));
    }

    public function trimLeft(string $characterMask = self::TRIM_MASK): PlainString
    {
        return new self(ltrim($this->value, $characterMask));
    }

    public function lower(): PlainString
    {
        return new self(mb_strtolower($this->value));
    }

    public function upper(): PlainString
    {
        return new self(mb_strtoupper($this->value));
    }

    public function lowerFirst(): PlainString
    {
        return $this->substring(0, 1)->lower()->postfix($this->substring(1));
    }

    public function upperFirst(): PlainString
    {
        return $this->substring(0, 1)->upper()->postfix($this->substring(1));
    }

    public function upperWords(): StringValue
    {
        return $this
            ->explode(' ')
            ->map(function (StringValue $word): StringValue {
                return $word->upperFirst();
            })
            ->implode(' ');
    }

    public function padRight(int $length, string $string = ' '): PlainString
    {
        return new self(str_pad($this->value, $length, $string, STR_PAD_RIGHT));
    }

    public function padLeft(int $length, string $string = ' '): PlainString
    {
        return new self(str_pad($this->value, $length, $string, STR_PAD_LEFT));
    }

    public function padBoth(int $length, string $string = ' '): PlainString
    {
        return new self(str_pad($this->value, $length, $string, STR_PAD_BOTH));
    }

    public function replace(string $search, string $replace): PlainString
    {
        return new self(str_replace($search, $replace, $this->value));
    }

    public function replacePattern(string $pattern, string $replacement): PlainString
    {
        return new self(preg_replace($pattern, $replacement, $this->value));
    }

    public function replacePatternCallback(string $pattern, callable $callback): PlainString
    {
        return new self(preg_replace_callback($pattern, $callback, $this->value));
    }

    public function matchPatterns(string $pattern): StringsArray
    {
        preg_match($pattern, $this->value, $matches);

        return Wrap::stringsArray($matches);
    }

    public function isMatching(string $pattern): bool
    {
        return $this->matchAllPatterns($pattern)->count() > 0;
    }

    public function matchAllPatterns(string $pattern): StringsArray
    {
        preg_match_all($pattern, $this->value, $matches, PREG_SET_ORDER);

        return Wrap::stringsArray($matches);
    }

    public function splitByPattern(string $pattern): StringsArray
    {
        return Wrap::stringsArray(preg_split($pattern, $this->value));
    }

    public function explode(string $delimiter): StringsArray
    {
        return Wrap::stringsArray(explode($delimiter, $this->value));
    }

    public function truncate(int $length, string $postfix = '...'): PlainString
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
        return ($pos = mb_strpos($this->value, $needle)) !== false ? $pos : null;
    }

    public function positionLast(string $needle): ?int
    {
        return ($pos = mb_strrpos($this->value, $needle)) !== false ? $pos : null;
    }

    public function toString(): string
    {
        return $this->value();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isEmpty(): bool
    {
        return $this->value === '';
    }
}
