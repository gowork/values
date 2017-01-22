<?php

namespace GW\Value;

interface Sortable
{
    /**
     * @param callable $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return Sortable
     */
    public function sort(callable $comparator);

    /**
     * @return Sortable
     */
    public function shuffle();
}
