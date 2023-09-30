<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;

final class Subtract implements Numberable
{
    public function __construct(
        private Numberable $minuend,
        private Numberable $subtrahend,
    ) {
    }

    public function toNumber(): float|int
    {
        return $this->minuend->toNumber() - $this->subtrahend->toNumber();
    }
}
