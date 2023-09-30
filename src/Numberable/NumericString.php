<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;
use LogicException;
use function is_numeric;

final class NumericString implements Numberable
{
    private int|float $number;

    /** @param numeric-string $number */
    public function __construct(string $number)
    {
        if (!is_numeric($number)) {
            throw new LogicException('Expected numeric-string got string');
        }

        $this->number = $number + 0;
    }

    public function toNumber(): int|float
    {
        return $this->number;
    }
}
