<?php declare(strict_types=1);

namespace GW\Value;

/**
 * @internal
 * @template TKey
 * @template TValue
 */
final class IterableValueIterator
{
    /** @var iterable<TKey, TValue> */
    private iterable $iterable;
    private bool $used = false;

    /**
     * @param iterable<TKey, TValue> $iterable
     */
    public function __construct(iterable $iterable)
    {
        $this->iterable = $iterable;
    }

    /**
     * @return iterable<TKey, TValue>
     */
    public function __invoke(): iterable
    {
        if ($this->used) {
            throw new \RuntimeException('IterableValue is already used.');
        }

        yield from $this->iterable;
        $this->used = $this->iterable instanceof \Generator;
    }
}
