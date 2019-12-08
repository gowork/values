<?php

namespace GW\Value;

/**
 * @template TValue
 * @extends Mappable<TValue>
 * @extends Filterable<TValue>
 */
interface Collection extends Filterable, Mappable, Sortable, Countable, Reversible
{
    /**
     * @return TValue
     */
    public function first();

    /**
     * @return TValue
     */
    public function last();

    /**
     * @param callable(TValue $value): bool $filter
     * @return TValue
     */
    public function find(callable $filter);

    /**
     * @param callable(TValue $value): bool $filter
     * @return TValue
     */
    public function findLast(callable $filter);

    /**
     * @param TValue $element
     */
    public function hasElement($element): bool;

    /**
     * @param callable(TValue $value): bool $filter
     */
    public function any(callable $filter): bool;

    /**
     * @param callable(TValue $value): bool $filter
     */
    public function every(callable $filter): bool;

    /**
     * @param callable(TValue $value): void $callback
     * @return Collection<TValue>
     */
    public function each(callable $callback): Collection;

    /**
     * @param callable(TValue $valueA, TValue $valueB):int|null $comparator
     * @return Collection<TValue>
     */
    public function unique(?callable $comparator = null): Collection;

    /**
     * @return array<int, TValue>
     */
    public function toArray(): array;

    /**
     * @param callable(TValue $value): bool $filter
     * @return Collection<TValue>
     */
    public function filter(callable $filter): Collection;

    /**
     * @return Collection<TValue>
     */
    public function filterEmpty(): Collection;

    /**
     * @template TNewValue
     * @param callable(TValue $value): TNewValue $transformer
     * @return Collection<TNewValue>
     */
    public function map(callable $transformer): Collection;

    /**
     * @param callable(TValue $valueA, TValue $valueB):int $comparator
     * @return Collection<TValue>
     */
    public function sort(callable $comparator): Collection;

    /**
     * @return Collection<TValue>
     */
    public function shuffle(): Collection;

    /**
     * @return Collection<TValue>
     */
    public function reverse(): Collection;
}
