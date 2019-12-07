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
     * @param callable $filter function(mixed $value): bool
     * @return mixed
     */
    public function find(callable $filter);

    /**
     * @param callable $filter function(mixed $value): bool
     * @return mixed
     */
    public function findLast(callable $filter);

    /**
     * @param mixed $element
     */
    public function hasElement($element): bool;

    /**
     * @param callable $filter function(mixed $value): bool
     */
    public function any(callable $filter): bool;

    /**
     * @param callable $filter function(mixed $value): bool
     */
    public function every(callable $filter): bool;

    /**
     * @param callable $callback function(mixed $value): void
     * @return Collection
     */
    public function each(callable $callback): Collection;

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return Collection
     */
    public function unique(?callable $comparator = null): Collection;

    /**
     * @return mixed[]
     */
    public function toArray(): array;

    /**
     * @param callable $filter function(mixed $value): bool
     * @return Collection
     */
    public function filter(callable $filter): Collection;

    /**
     * @return Collection
     */
    public function filterEmpty(): Collection;

    /**
     * @param callable $transformer function(mixed $value): mixed
     * @return Collection
     */
    public function map(callable $transformer): Collection;

    /**
     * @param callable $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return Collection
     */
    public function sort(callable $comparator): Collection;

    /**
     * @return Collection
     */
    public function shuffle(): Collection;

    /**
     * @return Collection
     */
    public function reverse(): Collection;
}
