<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;

final class Modulo implements Numberable
{
    public function __construct(
        private Numberable $dividend,
        private Numberable $divisor,
    ) {
    }

    public function toNumber(): int
    {
        return $this->dividend->toNumber() % $this->divisor->toNumber();
    }
}
