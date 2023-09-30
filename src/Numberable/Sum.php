<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Arrayable;
use GW\Value\Numberable;
use function array_reduce;

final class Sum implements Numberable
{
    public function __construct(
        /** @var Arrayable<Numberable> */
        private Arrayable $terms,
    ) {
    }

    public function toNumber(): float|int
    {
        return array_reduce($this->terms->toArray(), new SumReducer(), 0);
    }
}
