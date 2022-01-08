<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;

final class Formula implements Numberable
{
    private Numberable $numberable;
    /** @var callable(int|float):(int|float) */
    private $calculation;

    /**
     * @param callable(int|float):(int|float) $calculation
     */
    public function __construct(Numberable $numberable, callable $calculation)
    {
        $this->numberable = $numberable;
        $this->calculation = $calculation;
    }

    /** @return int|float */
    public function toNumber()
    {
        return ($this->calculation)($this->numberable->toNumber());
    }
}
