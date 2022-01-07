<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;

final class Subtract implements Numberable
{
    private Numberable $minuend;
    private Numberable $subtrahend;

    public function __construct(Numberable $minuend, Numberable $subtrahend)
    {
        $this->minuend = $minuend;
        $this->subtrahend = $subtrahend;
    }

    /** @return int|float */
    public function toNumber()
    {
        return $this->minuend->toNumber() - $this->subtrahend->toNumber();
    }
}
