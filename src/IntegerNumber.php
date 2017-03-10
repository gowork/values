<?php declare(strict_types=1);

namespace GW\Value;

final class IntegerNumber implements NumberValue
{
    /** @var int */
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function zero(): self
    {
        return new self(0);
    }

    /**
     * @return int Number of decimal places, ie. 1234.12 has scale = 2
     */
    public function scale(): int
    {
        return 0;
    }

    public function isInteger(): bool
    {
        return true;
    }

    public function isDecimal(): bool
    {
        return false;
    }

    public function toInt(): int
    {
        return $this->value;
    }

    public function toFloat(): float
    {
        return (float)$this->value;
    }

    public function toString(): string
    {
        return (string)$this->value;
    }

    public function toStringValue(): StringValue
    {
        return Wrap::string($this->toString());
    }

    public function format(int $scale = 0, string $point = '.', string $thousandsSeparator = ','): StringValue
    {
        return Wrap::string(\number_format($this->value, $scale, $point, $thousandsSeparator));
    }

    /**
     * @return int {-1, 0, 1}
     */
    public function compare(NumberValue $other): int
    {
        return $other->isInteger() ? $this->toInt() <=> $other->toInt() : $this->toFloat() <=> $other->toFloat();
    }

    public function equals(NumberValue $other): bool
    {
        return $this->compare($other) === 0;
    }

    public function greaterThan(NumberValue $other): bool
    {
        return $this->compare($other) === 1;
    }

    public function lesserThan(NumberValue $other): bool
    {
        return $this->compare($other) === -1;
    }

    /**
     * @return NumberValue
     */
    public function add(NumberValue $other)
    {
        return $other->isInteger()
            ? $this->withValue($this->value + $other->toInt())
            : Wrap::number($this->toFloat() + $other->toFloat());
    }

    /**
     * @return NumberValue
     */
    public function subtract(NumberValue $other)
    {
        return $other->isInteger()
            ? $this->withValue($this->value - $other->toInt())
            : Wrap::number($this->toFloat() - $other->toFloat());
    }

    /**
     * @return NumberValue
     */
    public function multiply(NumberValue $other)
    {
        return $other->isInteger()
            ? $this->withValue($this->value * $other->toInt())
            : Wrap::number($this->toFloat() * $other->toFloat());
    }

    /**
     * @return NumberValue
     */
    public function divide(NumberValue $other)
    {
        return Wrap::number($this->value / $other->toFloat());
    }

    /**
     * @return NumberValue
     */
    public function abs()
    {
        return $this->withValue(\abs($this->value));
    }

    /**
     * @return NumberValue
     */
    public function round(int $scale = 0, ?int $roundMethod = null)
    {
        if ($scale === 0) {
            return $this;
        }

        return Wrap::number(\round($this->value, $scale, $roundMethod ?? self::ROUND_DEFAULT));
    }

    /**
     * @return NumberValue
     */
    public function floor(int $scale = 0)
    {
        if ($scale === 0) {
            return $this;
        }

        $shift = 10 ** $scale;

        return Wrap::number(\floor($this->value * $shift) / $shift);
    }

    /**
     * @return NumberValue
     */
    public function ceil(int $scale = 0)
    {
        if ($scale === 0) {
            return $this;
        }

        $shift = 10 ** $scale;

        return Wrap::number(\floor($this->value * $shift) / $shift);
    }

    /**
     * @return bool false when 0, true otherwise
     */
    public function isEmpty(): bool
    {
        return $this->value === 0;
    }

    private function withValue(int $value): self
    {
        return $value === $this->value ? $this : new self($value);
    }
}
