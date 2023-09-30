<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;
use function round;
use const PHP_ROUND_HALF_UP;

final class Round implements Numberable
{
    /** @var int<1,4> */
    private int $mode;

    public function __construct(
        private Numberable $numberable,
        private int $precision,
        ?int $mode = null,
    ) {
        $this->mode = $mode >= 1 && $mode <= 4 ? $mode : PHP_ROUND_HALF_UP;
    }

    public function toNumber(): float
    {
        return round($this->numberable->toNumber(), $this->precision, $this->mode);
    }
}
