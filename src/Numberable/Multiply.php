<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;

final class Multiply implements Numberable
{
    public function __construct(
        private Numberable $left,
        private Numberable $right,
    ) {
    }

    public function toNumber(): float|int
    {
        return $this->left->toNumber() * $this->right->toNumber();
    }
}
