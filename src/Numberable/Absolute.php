<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;
use function abs;

final class Absolute implements Numberable
{
    public function __construct(
        private Numberable $numberable,
    ) {
    }

    public function toNumber(): float|int
    {
        return abs($this->numberable->toNumber());
    }
}
