<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_values;
use function uasort;

/**
 * @template TValue
 * @implements Arrayable<TValue>
 */
final class Sort implements Arrayable
{
    /** @var Arrayable<TValue> */
    private Arrayable $arrayable;
    /** @var callable(TValue,TValue):int */
    private $comparator;

    /**
     * @param Arrayable<TValue> $arrayable
     * @param callable(TValue,TValue):int $comparator
     */
    public function __construct(Arrayable $arrayable, callable $comparator)
    {
        $this->arrayable = $arrayable;
        $this->comparator = $comparator;
    }

    /** @return TValue[] */
    public function toArray(): array
    {
        $items = $this->arrayable->toArray();
        uasort($items, $this->comparator);

        return array_values($items);
    }
}
