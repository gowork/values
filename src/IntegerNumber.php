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

    public static function one(): self
    {
        return new self(1);
    }

    /**
     * @return int Number of decimal places, ie. 1234.12 has scale = 2
     */
    public function scale(): int
    {
        return 0;
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

    public function __toString(): string
    {
        return $this->toString();
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
        return $other->scale() < 1 ? $this->toInt() <=> $other->toInt() : $this->toFloat() <=> $other->toFloat();
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

    public function add(NumberValue $other): NumberValue
    {
        return $other->scale() < 1
            ? $this->withValue($this->value + $other->toInt())
            : Wrap::number($this->toFloat() + $other->toFloat());
    }

    public function subtract(NumberValue $other): NumberValue
    {
        return $other->scale() < 1
            ? $this->withValue($this->value - $other->toInt())
            : Wrap::number($this->toFloat() - $other->toFloat());
    }

    public function multiply(NumberValue $other): NumberValue
    {
        return $other->scale() < 1
            ? $this->withValue($this->value * $other->toInt())
            : Wrap::number($this->toFloat() * $other->toFloat());
    }

    public function divide(NumberValue $other): NumberValue
    {
        if ($other->toFloat() === 0.0) {
            throw new \DivisionByZeroError('Cannot divide by 0');
        }

        if ($other->equals(self::one())) {
            return $this;
        }

        return Wrap::number($this->value / $other->toFloat());
    }

    public function abs(): IntegerNumber
    {
        return $this->withValue((int)\abs($this->value));
    }

    public function round(int $scale = 0, ?int $roundMode = null): NumberValue
    {
        if ($scale === 0) {
            return $this;
        }

        return $this->withScaledValue(\round($this->value, $scale, $roundMode ?? self::DEFAULT_ROUND_MODE), $scale);
    }

    public function floor(int $scale = 0): NumberValue
    {
        if ($scale === 0) {
            return $this;
        }

        $shift = 10 ** $scale;

        return $this->withScaledValue(\floor($this->value * $shift) / $shift, $scale);
    }

    public function ceil(int $scale = 0): NumberValue
    {
        if ($scale === 0) {
            return $this;
        }

        $shift = 10 ** $scale;

        return $this->withScaledValue(\ceil($this->value * $shift) / $shift, $scale);
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

    private function withScaledValue(float $value, int $scale): NumberValue
    {
        return $scale <= 0 ? $this->withValue((int)$value) : new FloatNumber($value);
    }
}
