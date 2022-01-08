<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\NumberValue;
use GW\Value\PlainNumber;

final class ToNumberValue
{
    private ToNumberable $toNumberable;

    public function __construct()
    {
        $this->toNumberable = new ToNumberable();
    }

    /** @param mixed $number */
    public function __invoke($number): NumberValue
    {
        $numberable = ($this->toNumberable)($number);

        if ($numberable instanceof NumberValue) {
            return $numberable;
        }

        return new PlainNumber($numberable);
    }
}
