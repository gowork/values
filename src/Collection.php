<?php declare(strict_types=1);

namespace GW\Value;

/**
 * @template TValue
 * @extends Mappable<TValue>
 * @extends Filterable<TValue>
 */
interface Collection extends Filterable, Mappable, Sortable, Countable, Reversible
{
    /**
     * @phpstan-return TValue
     */
    public function first();

    /**
     * @phpstan-return TValue
     */
    public function last();

    /**
     * @param callable(TValue $value): bool $filter
     * @phpstan-return TValue
     */
    public function find(callable $filter);

    /**
     * @param callable(TValue $value): bool $filter
     * @phpstan-return TValue
     */
    public function findLast(callable $filter);

    /**
     * @phpstan-param TValue $element
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
     * @phpstan-return Collection<TValue>
     */
    public function each(callable $callback): Collection;

    /**
     * @param callable(TValue $valueA, TValue $valueB):int|null $comparator
     * @phpstan-return Collection<TValue>
     */
    public function unique(?callable $comparator = null): Collection;

    /**
     * @phpstan-return array<int, TValue>
     */
    public function toArray(): array;

    /**
     * @param callable(TValue $value): bool $filter
     * @phpstan-return Collection<TValue>
     */
    public function filter(callable $filter): Collection;

    /**
     * @phpstan-return Collection<TValue>
     */
    public function filterEmpty(): Collection;

    /**
     * @template TNewValue
     * @param callable(TValue $value): TNewValue $transformer
     * @phpstan-return Collection<TNewValue>
     */
    public function map(callable $transformer): Collection;

    /**
     * @param callable(TValue $valueA, TValue $valueB):int $comparator
     * @phpstan-return Collection<TValue>
     */
    public function sort(callable $comparator): Collection;

    /**
     * @phpstan-return Collection<TValue>
     */
    public function shuffle(): Collection;

    /**
     * @phpstan-return Collection<TValue>
     */
    public function reverse(): Collection;
}
