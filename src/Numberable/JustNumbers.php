<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Arrayable;
use GW\Value\Arrayable\JustArray;
use GW\Value\Arrayable\Map;
use GW\Value\Numberable;

/**
 * @implements Arrayable<Numberable>
 */
final class JustNumbers implements Arrayable
{
    /** @var Arrayable<Numberable> */
    private Arrayable $numbers;

    /** @param float|int|numeric-string|Numberable ...$numbers */
    public function __construct(float|int|string|Numberable ...$numbers)
    {
        $this->numbers = new Map(new JustArray($numbers), new ToNumberable());
    }

    /**
     * @return Numberable[]
     */
    public function toArray(): array
    {
        return $this->numbers->toArray();
    }
}
