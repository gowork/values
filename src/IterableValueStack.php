<?php declare(strict_types=1);

namespace GW\Value;

/**
 * @internal
 * @template TKey
 * @template TValue
 */
final class IterableValueStack
{
    /** @var IterableValueIterator<TKey, TValue> */
    private IterableValueIterator $iterable;

    /** @var callable[] */
    private array $modifiers = [];

    /**
     * @param IterableValueIterator<TKey, TValue> $iterable
     */
    public function __construct(IterableValueIterator $iterable)
    {
        $this->iterable = $iterable;
    }

    /**
     * @param IterableValueIterator<mixed, mixed> $iterable
     */
    public function replaceIterator(IterableValueIterator $iterable): self
    {
        $clone = clone $this;
        $clone->iterable = $iterable;

        return $clone;
    }

    /**
     * @template TNewKey
     * @template TNewValue
     * @param callable(iterable<TKey, TValue> $value): iterable<TNewKey, TNewValue> $modifier
     * @return IterableValueStack<TNewKey, TNewValue>
     */
    public function push(callable $modifier): self
    {
        $clone = clone $this;
        $clone->modifiers[] = $modifier;

        return $clone;
    }

    /**
     * @return iterable<TKey, TValue>
     */
    public function iterate(): iterable
    {
        $values = ($this->iterable)();
        $i = \count($this->modifiers) - 1;

        $next = function () use (&$values, &$i, &$next) {
            if (!isset($this->modifiers[$i])) {
                yield from $values;
                return;
            }

            yield from $this->modifiers[$i--]($next());
        };

        yield from $next();
    }
}
