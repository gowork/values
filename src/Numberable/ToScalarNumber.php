<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;

final class ToScalarNumber
{
    public function __invoke(Numberable $value): float|int
    {
        return $value->toNumber();
    }
}
