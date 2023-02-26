<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use DivisionByZeroError;
use GW\Value\Arrayable;
use GW\Value\Arrayable\JustArray;
use GW\Value\Numberable;

final class Average implements Numberable
{
    public function __construct(
        /** @var Arrayable<Numberable> */
        private Arrayable $terms,
    ) {
    }

    public function toNumber(): float|int
    {
        $terms = $this->terms->toArray();
        $count = count($terms);
        if ($count === 0) {
            throw new DivisionByZeroError('Cannot calculate avg number from empty set');
        }

        return (new Sum(new JustArray($terms)))->toNumber() / $count;
    }
}
