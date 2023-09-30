<?php declare(strict_types=1);

namespace GW\Value;

use GW\Value\Numberable\Absolute;
use GW\Value\Numberable\Add;
use GW\Value\Numberable\Calculate;
use GW\Value\Numberable\Ceil;
use GW\Value\Numberable\Divide;
use GW\Value\Numberable\Floor;
use GW\Value\Numberable\JustNumber;
use GW\Value\Numberable\Modulo;
use GW\Value\Numberable\Multiply;
use GW\Value\Numberable\Round;
use GW\Value\Numberable\Subtract;
use function is_int;
use function number_format;
use const PHP_FLOAT_DIG;

final class PlainNumber implements NumberValue
{
    private Numberable $number;

    public function __construct(Numberable $number)
    {
        $this->number = $number;
    }

    /** @param float|int|numeric-string|Numberable $number */
    public static function from(float|int|string|Numberable $number): self
    {
        return new self(JustNumber::wrap($number));
    }

    public function format(int $decimals = 0, string $separator = '.', string $thousandsSeparator = ','): StringValue
    {
        return Wrap::string(number_format($this->number->toNumber(), $decimals, $separator, $thousandsSeparator));
    }

    /**
     * @param float|int|numeric-string|Numberable $other
     * @return int<-1,1>
     */
    public function compare(float|int|string|Numberable $other): int
    {
        return $this->toNumber() <=> JustNumber::wrap($other)->toNumber();
    }

    /** @param float|int|numeric-string|Numberable $other */
    public function equals(float|int|string|Numberable $other): bool
    {
        return $this->compare($other) === 0;
    }

    /** @param float|int|numeric-string|Numberable $other */
    public function add(float|int|string|Numberable $other): NumberValue
    {
        return new self(new Add($this->number, JustNumber::wrap($other)));
    }

    /** @param float|int|numeric-string|Numberable $other */
    public function subtract(float|int|string|Numberable $other): NumberValue
    {
        return new self(new Subtract($this->number, JustNumber::wrap($other)));
    }

    /** @param float|int|numeric-string|Numberable $other */
    public function multiply(float|int|string|Numberable $other): NumberValue
    {
        return new self(new Multiply($this->number, JustNumber::wrap($other)));
    }

    /** @param float|int|numeric-string|Numberable $other */
    public function divide(float|int|string|Numberable $other): NumberValue
    {
        return new self(new Divide($this->number, JustNumber::wrap($other)));
    }

    /** @param float|int|numeric-string|Numberable $divider */
    public function modulo(float|int|string|Numberable $divider): NumberValue
    {
        return new self(new Modulo($this->number, JustNumber::wrap($divider)));
    }

    public function abs(): NumberValue
    {
        return new self(new Absolute($this->number));
    }

    public function round(int $precision = 0, ?int $roundMode = null): NumberValue
    {
        return new self(new Round($this->number, $precision, $roundMode));
    }

    public function floor(): NumberValue
    {
        return new self(new Floor($this->number));
    }

    public function ceil(): NumberValue
    {
        return new self(new Ceil($this->number));
    }

    /** @param callable(int|float):(int|float|Numberable) $formula */
    public function calculate(callable $formula): NumberValue
    {
        return new self(new Calculate($this->number, $formula));
    }

    public function isEmpty(): bool
    {
        return $this->toFloat() === 0.0;
    }

    public function toNumber(): float|int
    {
        return $this->number->toNumber();
    }

    public function toInteger(): int
    {
        return (int)$this->number->toNumber();
    }

    public function toFloat(): float
    {
        return (float)$this->number->toNumber();
    }

    public function toStringValue(): StringValue
    {
        $number = $this->number->toNumber();
        if (is_int($number)) {
            return Wrap::string((string)$number);
        }

        $value = $this->format(PHP_FLOAT_DIG, '.', '')->trimRight('0');

        return $value->endsWith('.') ? $value->postfix('0') : $value;
    }

    public function __toString(): string
    {
        return $this->toStringValue()->toString();
    }
}
