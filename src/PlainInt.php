<?php declare(strict_types=1);

namespace GW\Value;

final class PlainInt implements IntValue
{
    /** @var int */
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @param int|IntValue $number
     */
    public function add($number): IntValue
    {
        return new self($this->value + $this->num($number));
    }

    /**
     * @param int|IntValue $number
     */
    public function substract($number): IntValue
    {
        return new self($this->value - $this->num($number));
    }

    /**
     * @param int|IntValue $number
     */
    public function multiply($number): IntValue
    {
        return new self($this->value * $this->num($number));
    }

    public function toString(): string
    {
        return (string)$this->value;
    }

    public function toInt(): int
    {
        return $this->value;
    }

    /**
     * @param int|IntValue $number
     */
    private function num($number): int
    {
        return $number instanceof IntValue ? $number->toInt() : $number;
    }
}
