<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;
use LogicException;
use function is_float;
use function is_int;
use function is_numeric;
use function is_string;

final class ToNumberable
{
    public function __invoke(mixed $number): Numberable
    {
        if ($number instanceof Numberable) {
            return $number;
        }

        if (is_int($number)) {
            return new JustInteger($number);
        }

        if (is_float($number)) {
            return new JustFloat($number);
        }

        if (is_string($number) && is_numeric($number)) {
            return new NumericString($number);
        }

        throw new LogicException('Cannot cast value to Numberable');
    }
}
