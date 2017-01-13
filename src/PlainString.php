<?php

namespace GW\Value;

final class PlainString implements StringValue
{
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function length(): int
    {
        return mb_strlen($this->value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
