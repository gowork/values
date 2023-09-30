<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;

final class SumReducer
{
    public function __invoke(float|int $sum, Numberable $next): float|int
    {
        return $sum + $next->toNumber();
    }
}
