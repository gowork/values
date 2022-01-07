<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;

final class Divide implements Numberable
{
    private Numberable $dividend;
    private Numberable $divisor;

    public function __construct(Numberable $dividend, Numberable $divisor)
    {
        $this->dividend = $dividend;
        $this->divisor = $divisor;
    }

    /** @return int|float */
    public function toNumber()
    {
        return $this->dividend->toNumber() / $this->divisor->toNumber();
    }
}
