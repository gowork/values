<?php

namespace GW\Value;

interface Collection extends Filterable, Mappable, Sortable, Countable, Reversible
{
    /**
     * @return mixed
     */
    public function first();

    /**
     * @return mixed
     */
    public function last();

    /**
     * @param mixed $element
     */
    public function hasElement($element): bool;

    /**
     * @param callable $callback function(mixed $value): void
     * @return Collection
     */
    public function each(callable $callback);

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return Collection
     */
    public function unique(?callable $comparator = null);

    /**
     * @return mixed[]
     */
    public function toArray(): array;

    /**
     * @param callable $transformer function(mixed $value): bool { ... }
     * @return Collection
     */
    public function filter(callable $transformer);

    /**
     * @return Collection
     */
    public function filterEmpty();

    /**
     * @param callable $transformer function(mixed $value): mixed { ... }
     * @return Collection
     */
    public function map(callable $transformer);

    /**
     * @param callable $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return Collection
     */
    public function sort(callable $comparator);

    /**
     * @return Collection
     */
    public function shuffle();

    /**
     * @return Collection
     */
    public function reverse();
}
