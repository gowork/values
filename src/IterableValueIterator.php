<?php declare(strict_types=1);

namespace GW\Value;

/**
 * @internal
 */
final class IterableValueIterator
{
    /** @var iterable */
    private $iterable;

    /** @var bool */
    private $used = false;

    public function __construct(iterable $iterable)
    {
        $this->iterable = $iterable;
    }

    public function replaceIterable($iterable): void
    {
        $this->iterable = $iterable;
        $this->used = false;
    }

    public function __invoke(): iterable
    {
        if ($this->used) {
            throw new \RuntimeException('IterableValue is already used.');
        }

        yield from $this->iterable;
        $this->used = $this->iterable instanceof \Generator;
    }
}
