<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use DivisionByZeroError;
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
        $divisor = $this->divisor->toNumber();

        if ($divisor === 0) {
            // bypass warning triggered by PHP 7.4 before throwing DivisionByZeroError
            throw new DivisionByZeroError('Division by zero');
        }

        return $this->dividend->toNumber() / $divisor;
    }
}
