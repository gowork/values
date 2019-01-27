<?php

namespace GW\Value;

final class FixedNumber implements NumberValue
{
    public const DEFAULT_SCALE = 2;

    /** @var int */
    private $bits;

    /** @var int */
    private $scale;

    /** @var int */
    private $roundMode;

    public function __construct(int $bits, int $scale, ?int $roundMode = null)
    {
        $this->bits = $bits;
        $this->scale = $scale;
        $this->roundMode = $roundMode ?? self::DEFAULT_ROUND_MODE;
    }

    public static function fromBits(int $bits, int $scale, ?int $roundMode = null)
    {
        return new self($bits, $scale, $roundMode);
    }

    /**
     * @param int|float|string $number
     */
    public static function from($number, ?int $scale = null, ?int $roundMode = null): self
    {
        if ($number instanceof self) {
            return $number->copyWith($number->bits, $scale, $roundMode);
        }

        if ($number instanceof NumberValue) {
            return self::fromFloat($number->toFloat(), $scale, $roundMode);
        }

        if (\is_int($number)) {
            return self::fromInt($number, $scale, $roundMode);
        }

        if (\is_float($number)) {
            return self::fromFloat($number, $scale, $roundMode);
        }

        if (\is_string($number)) {
            return self::fromString($number, $scale, $roundMode);
        }

        throw new \InvalidArgumentException(\sprintf('Value `%s` is not a valid number', $number));
    }

    public static function fromInt(int $number, ?int $scale = null, ?int $roundMode = null): self
    {
        $scale = $scale ?? self::DEFAULT_SCALE;
        $bits = $number * (int)(10 ** $scale);

        return new self($bits, $scale, $roundMode);
    }

    public static function fromFloat(float $number, int $scale, ?int $roundMode = null): self
    {
        $roundMode = $roundMode ?? self::DEFAULT_ROUND_MODE;
        $bits = (int)\round($number * (10 ** $scale), 0, $roundMode);

        return new self($bits, $scale, $roundMode);
    }

    public static function fromString(string $input, ?int $scale = null, ?int $roundMode = null): self
    {
        // strip formatting
        $number = Wrap::string($input)->replacePattern('/[\s,]/', '');
        if (!\is_numeric($number->toString())) {
            throw new \InvalidArgumentException("String `{$number}` is not a valid number");
        }

        // guess scale
        $dotPosition = $number->position('.');
        $realScale = $dotPosition !== null ? $number->length() - ($dotPosition + 1) : 0;
        $bits = (int)$number->replace('.', '')->toString();

        return self::fromBits($bits, $realScale, $roundMode)->withScale($scale ?? $realScale);
    }

    public function scale(): int
    {
        return $this->scale;
    }

    public function toInt(): int
    {
        return (int)$this->toFloat();
    }

    public function toFloat(): float
    {
        return (float)($this->bits / $this->pointShift());
    }

    public function toStringValue(): StringValue
    {
        return Wrap::string($this->toString());
    }

    public function toString(): string
    {
        if ($this->scale === 0) {
            return (string)$this->bits;
        }

        if ($this->scale < 0) {
            return (string)(int)($this->bits / $this->pointShift());
        }

        return \number_format($this->toFloat(), $this->scale, '.', '');
    }

    public function format(int $scale = 0, string $point = '.', string $thousandsSeparator = ','): StringValue
    {
        return Wrap::string(\number_format($this->toFloat(), $scale, $point, $thousandsSeparator));
    }

    public function greaterThan(NumberValue $other): bool
    {
        return $this->compare($other) === 1;
    }

    /**
     * @return int {-1, 0, 1}
     */
    public function compare(NumberValue $other): int
    {
        return $this->bits <=> $this->fixNumber($other)->bits;
    }

    public function lesserThan(NumberValue $other): bool
    {
        return $this->compare($other) === -1;
    }

    public function add(NumberValue $other): self
    {
        return $this->withBits($this->bits + $this->fixNumber($other)->bits);
    }

    public function subtract(NumberValue $other): self
    {
        return $this->withBits($this->bits - $this->fixNumber($other)->bits);
    }

    public function multiply(NumberValue $other): self
    {
        $other = $this->fixNumber($other, $other->scale());

        return $this->withBits($this->bits * $other->bits, $this->scale + $other->scale);
    }

    public function divide(NumberValue $other): self
    {
        $other = $this->fixNumber($other, $other->scale());

        if ($other->bits === 0) {
            throw new \DivisionByZeroError('Cannot divide by 0');
        }

        return $this->withBits($this->bits / $other->bits, $this->scale - $other->scale);
    }

    public function abs(): self
    {
        return $this->withBits(\abs($this->bits));
    }

    public function round(int $scale = 0, ?int $roundMode = null): self
    {
        if ($scale >= $this->scale) {
            return $this;
        }

        return $this->withBits(\round($this->bits, $scale - $this->scale, $roundMode ?? $this->roundMode));
    }

    public function floor(int $scale = 0): self
    {
        if ($scale >= $this->scale) {
            return $this;
        }

        $shift = 10 ** $scale;
        $float = \floor($this->toFloat() * $shift) / $shift;

        return $this->withBits($float, 0);
    }

    public function ceil(int $scale = 0): self
    {
        if ($scale >= $this->scale) {
            return $this;
        }

        $shift = 10 ** $scale;
        $float = \ceil($this->toFloat() * $shift) / $shift;

        return $this->withBits($float, 0);
    }

    /**
     * @return bool false when 0, true otherwise
     */
    public function isEmpty(): bool
    {
        return $this->equals(self::fromInt(0));
    }

    public function equals(NumberValue $other): bool
    {
        return $this->compare($other) === 0;
    }

    public function withScale(int $scale): self
    {
        if ($scale === $this->scale) {
            return $this;
        }

        $shift = 10 ** ($scale - $this->scale);
        $bits = (int)\round($this->bits * $shift, 0, $this->roundMode);

        return $this->copyWith($bits, $scale);
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return int|float
     */
    private function pointShift()
    {
        return 10 ** $this->scale;
    }

    private function fixNumber(NumberValue $other, ?int $scale = null): FixedNumber
    {
        $scale = $scale ?? $this->scale;

        if ($other instanceof self) {
            return $other->withScale($scale);
        }

        return self::fromFloat($other->toFloat(), $scale, $this->roundMode);
    }

    /**
     * @param int|float $bits
     */
    private function withBits($bits, ?int $fromScale = null): self
    {
        $fromScale = $fromScale ?? $this->scale;
        $scaleDiff = $this->scale - $fromScale;
        $bits = (int)\round($bits * (10 ** $scaleDiff), 0, $this->roundMode);

        if ($bits === $this->bits) {
            return $this;
        }

        return $this->copyWith($bits);
    }

    private function copyWith(?int $bits, ?int $scale = null, ?int $roundMode = null): self
    {
        $bits = $bits ?? $this->bits;
        $scale = $scale ?? $this->scale;
        $roundMode = $roundMode ?? $this->roundMode;

        if ($this->bits === $bits && $this->scale === $scale && $this->roundMode === $roundMode) {
            return $this;
        }

        return new self($bits, $scale, $roundMode);
    }
}
