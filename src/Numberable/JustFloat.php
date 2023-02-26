<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;

final class JustFloat implements Numberable
{
    public function __construct(
        private float $float,
    ) {
    }

    public function toNumber(): float
    {
        return $this->float;
    }
}
