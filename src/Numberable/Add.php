<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;

final class Add implements Numberable
{
    private Numberable $leftTerm;
    private Numberable $rightTerm;

    public function __construct(Numberable $leftTerm, Numberable $rightTerm)
    {
        $this->leftTerm = $leftTerm;
        $this->rightTerm = $rightTerm;
    }

    /** @return int|float */
    public function toNumber()
    {
        return $this->leftTerm->toNumber() + $this->rightTerm->toNumber();
    }
}
