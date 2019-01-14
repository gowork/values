<?php declare(strict_types=1);

namespace GW\Value;

/**
 * @internal
 */
final class IterableValueStack
{
    /** @var IterableValueIterator */
    private $iterable;

    /** @var callable[] */
    private $modifiers = [];

    public function __construct(iterable $iterable)
    {
        $this->iterable = new IterableValueIterator($iterable);
    }

    public function replaceSource(iterable $iterable): self
    {
        $clone = clone $this;
        $clone->iterable = new IterableValueIterator($iterable);

        return $clone;
    }

    /**
     * @param callable $modifier function (iterable): iterable
     */
    public function push(callable $modifier): self
    {
        $clone = clone $this;
        $clone->modifiers[] = $modifier;

        return $clone;
    }

    public function source(): iterable
    {
        return $this->iterable->iterable();
    }

    public function iterate(): iterable
    {
        $values = ($this->iterable)();
        $i = \count($this->modifiers) - 1;

        $next = function () use (&$values, &$i, &$next): iterable {
            if (!isset($this->modifiers[$i])) {
                return $values;
            }

            return $this->modifiers[$i--]($next());
        };

        yield from $next();
    }
}
