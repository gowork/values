<?php declare(strict_types=1);

namespace GW\Value;

use ArrayIterator;
use BadMethodCallException;
use GW\Value\Associable\Cache;
use GW\Value\Associable\Filter;
use GW\Value\Associable\Join;
use GW\Value\Associable\JustAssoc;
use GW\Value\Associable\Keys;
use GW\Value\Associable\Map;
use GW\Value\Associable\MapKeys;
use GW\Value\Associable\Merge;
use GW\Value\Associable\Only;
use GW\Value\Associable\Replace;
use GW\Value\Associable\Reverse;
use GW\Value\Associable\Shuffle;
use GW\Value\Associable\Sort;
use GW\Value\Associable\SortKeys;
use GW\Value\Associable\UniqueByComparator;
use GW\Value\Associable\UniqueByString;
use GW\Value\Associable\Values;
use GW\Value\Associable\WithItem;
use GW\Value\Associable\Without;
use Traversable;
use function array_key_exists;
use function count;
use function in_array;
use function is_array;

/**
 * @template TKey of int|string
 * @template TValue
 * @implements AssocValue<TKey, TValue>
 */
final class AssocArray implements AssocValue
{
    /** @var Associable<TKey,TValue> */
    private Associable $items;

    /**
     * @param array<TKey,TValue>|Associable<TKey,TValue> $items
     */
    public function __construct($items)
    {
        $this->items = is_array($items) ? new JustAssoc($items) : new Cache($items);
    }

    /**
     * @template TNewValue
     * @phpstan-param callable(TValue $value, TKey $key=):TNewValue $transformer
     * @phpstan-return AssocArray<TKey, TNewValue>
     */
    public function map(callable $transformer): AssocArray
    {
        return new self(new Map($this->items, $transformer));
    }

    /**
     * @phpstan-return ArrayValue<TKey>
     */
    public function keys(): ArrayValue
    {
        return new PlainArray(new Keys($this->items));
    }

    /**
     * @template TNewKey of int|string
     * @param callable(TKey $key, TValue $value=): TNewKey $transformer
     * @phpstan-return AssocArray<TNewKey, TValue>
     */
    public function mapKeys(callable $transformer): AssocArray
    {
        return new self(new MapKeys($this->items, $transformer));
    }

    /**
     * @phpstan-return AssocArray<TKey, TValue>
     */
    public function filterEmpty(): AssocArray
    {
        return $this->filter(Filters::notEmpty());
    }

    /**
     * @param callable(TValue $value):bool $filter
     * @phpstan-return AssocArray<TKey, TValue>
     */
    public function filter(callable $filter): AssocArray
    {
        return new self(new Filter($this->items, $filter));
    }

    /**
     * @param callable(TValue,TValue):int $comparator
     * @phpstan-return AssocArray<TKey, TValue>
     */
    public function sort(callable $comparator): AssocArray
    {
        return new self(new Sort($this->items, $comparator));
    }

    /**
     * @phpstan-return AssocArray<TKey, TValue>
     */
    public function reverse(): AssocArray
    {
        return new self(new Reverse($this->items));
    }

    /**
     * @phpstan-return AssocArray<TKey, TValue>
     */
    public function shuffle(): AssocArray
    {
        return new self(new Shuffle($this->items));
    }

    /**
     * @param callable(TKey $keyA, TKey $keyB): int $comparator
     * @return AssocArray<TKey, TValue>
     */
    public function sortKeys(callable $comparator): AssocArray
    {
        return new self(new SortKeys($this->items, $comparator));
    }

    /**
     * @phpstan-param callable(TValue $value, TKey $key=):void $callback
     * @phpstan-return AssocArray<TKey, TValue>
     */
    public function each(callable $callback): AssocArray
    {
        foreach ($this->items->toAssocArray() as $key => $item) {
            $callback($item, $key);
        }

        return $this;
    }

    /**
     * @phpstan-param (callable(TValue,TValue):int)|null $comparator
     * @phpstan-return AssocArray<TKey, TValue>
     */
    public function unique(?callable $comparator = null): AssocArray
    {
        if ($comparator === null) {
            return new self(new UniqueByString($this->items));
        }

        return new self(new UniqueByComparator($this->items, $comparator));
    }

    /**
     * @phpstan-param TKey $key
     * @phpstan-param TValue $value
     * @phpstan-return AssocArray<TKey, TValue>
     */
    public function with($key, $value): AssocArray
    {
        return new self(new WithItem($this->items, $key, $value));
    }

    /**
     * @phpstan-param AssocValue<TKey, TValue> $other
     * @phpstan-return AssocArray<TKey, TValue>
     */
    public function merge(AssocValue $other): AssocArray
    {
        return new self(new Merge($this->items, $other));
    }

    /**
     * @phpstan-param AssocValue<TKey, TValue> $other
     * @phpstan-return AssocArray<TKey, TValue>
     */
    public function join(AssocValue $other): AssocValue
    {
        return new self(new Join($this->items, $other));
    }

    /**
     * @phpstan-param AssocValue<TKey, TValue> $other
     * @phpstan-return AssocArray<TKey, TValue>
     */
    public function replace(AssocValue $other): AssocArray
    {
        return new self(new Replace($this->items, $other));
    }

    /**
     * @phpstan-param TKey ...$keys
     * @phpstan-return AssocArray<TKey, TValue>
     */
    public function without(...$keys): AssocArray
    {
        return new self(new Without($this->items, ...$keys));
    }

    /**
     * @param TKey ...$keys
     * @return AssocArray<TKey, TValue>
     */
    public function only(...$keys): AssocArray
    {
        return new self(new Only($this->items, ...$keys));
    }

    /**
     * @phpstan-param TValue $value
     * @phpstan-return AssocArray<TKey, TValue>
     */
    public function withoutElement($value): AssocArray
    {
        return $this->filter(Filters::notEqual($value));
    }

    /**
     * @template TNewValue
     * @param callable(TNewValue,TValue,TKey):TNewValue $transformer
     * @phpstan-param TNewValue $start
     * @phpstan-return TNewValue
     */
    public function reduce(callable $transformer, $start)
    {
        $reduced = $start;

        foreach ($this->items->toAssocArray() as $key => $value) {
            $reduced = $transformer($reduced, $value, $key);
        }

        return $reduced;
    }

    /**
     * @phpstan-param TKey $key
     * @phpstan-param ?TValue $default
     * @phpstan-return ?TValue
     */
    public function get($key, $default = null)
    {
        return $this->items->toAssocArray()[$key] ?? $default;
    }

    /**
     * @phpstan-param TKey $key
     */
    public function has($key): bool
    {
        return array_key_exists($key, $this->items->toAssocArray());
    }

    /**
     * @return ?TValue
     */
    public function first()
    {
        return $this->values()->first();
    }

    /**
     * @return ArrayValue<TValue>
     */
    public function values(): ArrayValue
    {
        return new PlainArray(new Values($this->items));
    }

    /**
     * @phpstan-return ?TValue
     */
    public function last()
    {
        return $this->values()->last();
    }

    /**
     * @param callable(TValue $value): bool $filter
     * @phpstan-return ?TValue
     */
    public function find(callable $filter)
    {
        return $this->values()->find($filter);
    }

    /**
     * @param callable(TValue $value): bool $filter
     * @phpstan-return ?TValue
     */
    public function findLast(callable $filter)
    {
        return $this->values()->findLast($filter);
    }

    /**
     * @phpstan-param TValue $element
     */
    public function hasElement($element): bool
    {
        return in_array($element, $this->items->toAssocArray(), true);
    }

    /**
     * @param callable(TValue $value):bool $filter
     */
    public function any(callable $filter): bool
    {
        return $this->values()->any($filter);
    }

    /**
     * @param callable(TValue $value):bool $filter
     */
    public function every(callable $filter): bool
    {
        return $this->values()->every($filter);
    }

    /**
     * @return array<int, TValue>
     */
    public function toArray(): array
    {
        return $this->values()->toArray();
    }

    /**
     * @return array<TKey, TValue>
     */
    public function toAssocArray(): array
    {
        return $this->items->toAssocArray();
    }

    /**
     * @return Traversable<TKey, TValue>
     */
    public function getIterator(): Traversable
    {
        yield from $this->items->toAssocArray();
    }

    public function count(): int
    {
        return count($this->items->toAssocArray());
    }

    public function isEmpty(): bool
    {
        return $this->items->toAssocArray() === [];
    }

    /**
     * @param TKey $offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->items->toAssocArray()[$offset]);
    }

    /**
     * @param TKey $offset
     * @return ?TValue
     */
    public function offsetGet($offset): mixed
    {
        return $this->items->toAssocArray()[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        throw new BadMethodCallException('AssocArray is immutable');
    }

    public function offsetUnset($offset): void
    {
        throw new BadMethodCallException('AssocArray is immutable');
    }
}
