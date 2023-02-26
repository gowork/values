<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;

final class Add implements Numberable
{
    public function __construct(
        private Numberable $leftTerm,
        private Numberable $rightTerm,
    ) {
    }

    public function toNumber(): float|int
    {
        return $this->leftTerm->toNumber() + $this->rightTerm->toNumber();
    }
}
