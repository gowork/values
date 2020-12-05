<?php

namespace GW\Value;

/**
 * @template TValue
 */
interface Sortable
{
    /**
     * @phpstan-param callable(TValue,TValue):int $comparator
     * @phpstan-return Sortable<TValue>
     */
    public function sort(callable $comparator): Sortable;

    /** @phpstan-return Sortable<TValue> */
    public function shuffle(): Sortable;
}
