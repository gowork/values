<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_values;
use function shuffle;

/**
 * @template TValue
 * @implements Arrayable<TValue>
 */
final class Shuffle implements Arrayable
{
    /** @var Arrayable<TValue> */
    private Arrayable $arrayable;

    /** @param Arrayable<TValue> $arrayable */
    public function __construct(Arrayable $arrayable)
    {
        $this->arrayable = $arrayable;
    }

    /** @return TValue[] */
    public function toArray(): array
    {
        $items = $this->arrayable->toArray();
        shuffle($items);;

        return array_values($items);
    }
}
