<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Numberable;
use function floor;

final class Floor implements Numberable
{
    private Numberable $numberable;

    public function __construct(Numberable $numberable)
    {
        $this->numberable = $numberable;
    }

    public function toNumber(): float
    {
        return (float)floor($this->numberable->toNumber());
    }
}
