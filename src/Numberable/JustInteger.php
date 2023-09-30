<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;

final class JustInteger implements Numberable
{
    public function __construct(
        private int $integer,
    ) {
    }

    public function toNumber(): int
    {
        return $this->integer;
    }
}
