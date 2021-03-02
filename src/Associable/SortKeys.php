<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Associable;
use function uksort;

/**
 * @template TKey
 * @template TValue
 * @implements Associable<TKey,TValue>
 */
final class SortKeys implements Associable
{
    /** @var Associable<TKey,TValue> */
    private Associable $associable;
    /** @var callable(TKey,TKey):int */
    private $comparator;

    /**
     * @param Associable<TKey,TValue> $associable
     * @param callable(TKey,TKey):int $comparator
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
        uksort($items, $this->comparator);

        return $items;
    }
}
