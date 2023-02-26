<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;
use function is_string;

final class JustNumber implements Numberable
{
    public function __construct(
        private float|int $number,
    ) {
    }

    /** @param float|int|numeric-string|Numberable $number */
    public static function wrap(float|int|string|Numberable $number): Numberable
    {
        if (is_string($number)) {
            return new NumericString($number);
        }

        if ($number instanceof Numberable) {
            return $number;
        }

        return new self($number);
    }

    public function toNumber(): float|int
    {
        return $this->number;
    }
}
