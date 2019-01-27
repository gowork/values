<?php declare(strict_types=1);

namespace GW\Value;

final class FloatNumber implements NumberValue
{
    /** @var float */
    private $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public static function zero(): self
    {
        return new self(0.0);
    }

    /**
     * @return int Number of decimal places, ie. 1234.12 has scale = 2
     */
    public function scale(): int
    {
        $string = $this->toStringValue();

        return $string->length() - $string->position('.') - 1;
    }

    public function toInt(): int
    {
        return (int)$this->value;
    }

    public function toFloat(): float
    {
        return $this->value;
    }

    public function toString(): string
    {
        $string = (string)$this->value;
        if (\stripos($string, 'e') !== false) {
            $string = \rtrim(\number_format($this->value, 128, '.', ''), '0');
        }

        return $string;
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
        return $this->toFloat() <=> $other->toFloat();
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

    public function add(NumberValue $other): FloatNumber
    {
        return $this->withValue($this->value + $other->toFloat());
    }

    public function subtract(NumberValue $other): FloatNumber
    {
        return $this->withValue($this->value - $other->toFloat());
    }

    public function multiply(NumberValue $other): FloatNumber
    {
        return $this->withValue($this->value * $other->toFloat());
    }

    public function divide(NumberValue $other): FloatNumber
    {
        if ($other->toFloat() === 0.0) {
            throw new \DivisionByZeroError('Cannot divide by 0');
        }

        return $this->withValue($this->value / $other->toFloat());
    }

    public function abs(): FloatNumber
    {
        return $this->withValue(\abs($this->value));
    }

    public function round(int $scale = 0, ?int $roundMode = null): NumberValue
    {
        $value = \round($this->value, $scale, $roundMode ?? self::DEFAULT_ROUND_MODE);

        return $this->withScaledValue($value, $scale);
    }

    public function floor(int $scale = 0): NumberValue
    {
        $shift = 10 ** $scale;

        return $this->withScaledValue(\floor($this->value * $shift) / $shift, $scale);
    }

    public function ceil(int $scale = 0): NumberValue
    {
        $shift = 10 ** $scale;

        return $this->withScaledValue(\ceil($this->value * $shift) / $shift, $scale);
    }

    public function isEmpty(): bool
    {
        return $this->value === 0.0;
    }

    private function withValue(float $value): self
    {
        return $value === $this->value ? $this : new self($value);
    }

    private function withScaledValue(float $value, int $scale): NumberValue
    {
        if ($this->value === $value) {
            return $this;
        }

        return  $scale === null || $scale > 0 ? new self($value) : new IntegerNumber((int)$value);
    }
}
