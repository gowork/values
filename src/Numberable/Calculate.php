<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;

final class Calculate implements Numberable
{
    private Numberable $numberable;
    /** @var callable(int|float):(int|float|Numberable) */
    private $formula;

    /** @param callable(int|float):(int|float|Numberable) $formula */
    public function __construct(Numberable $numberable, callable $formula)
    {
        $this->numberable = $numberable;
        $this->formula = $formula;
    }

    /** @return int|float */
    public function toNumber()
    {
        $result = ($this->formula)($this->numberable->toNumber());

        return JustNumber::wrap($result)->toNumber();
    }
}
