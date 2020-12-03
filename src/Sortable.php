<?php

namespace GW\Value;

/**
 * @template TValue
 */
interface Sortable
{
    /**
     * @phpstan-param callable(TValue,TValue):int $comparator
     */
    public function sort(callable $comparator): Sortable;

    public function shuffle(): Sortable;
}
