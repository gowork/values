<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Associable;
use function uasort;

/**
 * @template TKey
 * @template TValue
 * @implements Associable<TKey,TValue>
 */
final class Sort implements Associable
{
    /** @var Associable<TKey,TValue> */
    private Associable $associable;
    /** @var callable(TValue,TValue):int */
    private $comparator;

    /**
     * @param Associable<TKey,TValue> $associable
     * @param callable(TValue,TValue):int $comparator
     */
    public function __construct(Associable $associable, callable $comparator)
    {
        $this->associable = $associable;
        $this->comparator = $comparator;
    }

    /** @return array<TKey,TValue> */
    public function toAssocArray(): array
    {
        $items = $this->associable->toAssocArray();
        uasort($items, $this->comparator);

        return $items;
    }
}
