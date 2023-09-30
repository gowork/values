<?php declare(strict_types=1);

namespace GW\Value;

interface Numberable
{
    public function toNumber(): float|int;
}
