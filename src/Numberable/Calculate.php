<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;

final class Calculate implements Numberable
{
    /** @var callable(int|float):(int|float|Numberable) */
    private $formula;

    /** @param callable(int|float):(int|float|Numberable) $formula */
    public function __construct(
        private Numberable $numberable,
        callable $formula,
    ) {
        $this->formula = $formula;
    }

    public function toNumber(): float|int
    {
        $result = ($this->formula)($this->numberable->toNumber());

        return JustNumber::wrap($result)->toNumber();
    }
}
