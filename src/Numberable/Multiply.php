<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;

final class Multiply implements Numberable
{
    private Numberable $right;
    private Numberable $left;

    public function __construct(Numberable $left, Numberable $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    /** @return int|float */
    public function toNumber()
    {
        return $this->left->toNumber() * $this->right->toNumber();
    }
}
