<?php declare(strict_types=1);

namespace GW\Value\Numberable;

use GW\Value\Arrayable;
use GW\Value\Arrayable\JustArray;
use GW\Value\Arrayable\Map;
use GW\Value\Numberable;
use GW\Value\NumberValue;

/**
 * @implements Arrayable<NumberValue>
 */
final class NumberValues implements Arrayable
{
    /** @var Arrayable<NumberValue> */
    private Arrayable $numbers;

    /** @param float|int|numeric-string|Numberable ...$numbers */
    public function __construct(float|int|string|Numberable ...$numbers)
    {
        $this->numbers = new Map(new JustArray($numbers), new ToNumberValue());
    }

    /**
     * @return NumberValue[]
     */
    public function toArray(): array
    {
        return $this->numbers->toArray();
    }
}
