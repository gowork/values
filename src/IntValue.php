<?php

namespace GW\Value;

interface IntValue
{
    /**
     * @return IntValue
     * @param int|IntValue $number
     */
    public function add($number);

    /**
     * @return IntValue
     * @param int|IntValue $number
     */
    public function substract($number);

    /**
     * @return IntValue
     * @param int|IntValue $number
     */
    public function multiply($number);

    public function toString(): string;

    public function toInt(): int;
}
