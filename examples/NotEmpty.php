<?php declare(strict_types=1);

namespace doc\GW\Value;

use Error;
use GW\Value\Wrap;
use function array_filter;
use function array_map;
use function array_values;

function arrayValue(): void
{
    $numbersOrNulls = Wrap::array([1, 2, 3, null]);
    $onlyNumbers = $numbersOrNulls->notEmpty();

    Wrap::array([1, 2, 3])->map(static fn(int $n): int => 1);
    $onlyNumbers->map(static fn(int $n): int => 1);
}

function iterableValue(): void
{
    $numbersOrNulls = Wrap::iterable([1, 2, 3, null]);
    $onlyNumbers = $numbersOrNulls->notEmpty();

    Wrap::array([1, 2, 3])->map(static fn(int $n): int => 1);
    $onlyNumbers->map(static fn(int $n): int => 1);
}

/**
 * @template TValue
 */
interface FooCollection
{
    /**
     * @template TNewValue
     * @phpstan-param callable(TValue $value):TNewValue $transformer
     * @phpstan-return FooCollection<TNewValue>
     */
    public function map(callable $transformer): FooCollection;

    /**
     * @param callable(TValue $value):bool $filter
     * @phpstan-return FooCollection<TValue>
     */
    public function filter(callable $filter): FooCollection;

    /**
     * @phpstan-return FooCollection<TValue>
     */
    public function notEmpty(): FooCollection;
}

/**
 * @template TValue
 * @implements FooCollection<TValue>
 */
final class GenericFooCollection implements FooCollection
{
    /** @phpstan-var array<int, TValue> */
    private array $items;

    /**
     * @phpstan-param array<mixed, TValue> $items
     */
    public function __construct(array $items)
    {
        $this->items = array_values($items);
    }

    /**
     * @template TNewValue
     * @phpstan-param callable(TValue $value):TNewValue $transformer
     * @phpstan-return FooCollection<TNewValue>
     */
    public function map(callable $transformer): FooCollection
    {
        return new self(array_map($transformer, $this->items));
    }

    /**
     * @param callable(TValue $value):bool $filter
     * @phpstan-return FooCollection<TValue>
     */
    public function filter(callable $filter): FooCollection
    {
        return new self(array_filter($this->items, $filter));
    }

    /**
     * @phpstan-return FooCollection<TValue>
     */
    public function notEmpty(): FooCollection
    {
        return $this
            ->filter(static fn($value): bool => $value !== null)
            ->map(
                /**
                 * @param TValue|null $value
                 * @return TValue
                 */
                static function ($value) {
                    if ($value === null) {
                        throw new Error('impossibru');
                    }
                    return $value;
                }
            );
    }
}
