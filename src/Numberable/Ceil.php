<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;
use function ceil;

final class Ceil implements Numberable
{
    public function __construct(
        private Numberable $numberable,
    ) {
    }

    public function toNumber(): float
    {
        return (float)ceil($this->numberable->toNumber());
    }
}
