<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;
use function round;
use const PHP_ROUND_HALF_UP;

final class Round implements Numberable
{
    private Numberable $numberable;
    private int $precision;
    private ?int $mode;

    public function __construct(Numberable $numberable, int $precision, ?int $mode = null)
    {
        $this->numberable = $numberable;
        $this->precision = $precision;
        $this->mode = $mode;
    }

    public function toNumber(): float
    {
        return round($this->numberable->toNumber(), $this->precision, $this->mode ?? PHP_ROUND_HALF_UP);
    }
}
