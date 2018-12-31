<?php declare(strict_types=1);

namespace GW\Value;

/**
 * @internal
 */
final class IterableValueStack
{
    /** @var IterableValueIterator */
    private $iterable;

    /** @var array[] */
    private $modifiers = [];

    public function __construct(IterableValueIterator $iterable)
    {
        $this->iterable = $iterable;
    }

    public function replaceIterator(IterableValueIterator $iterable): self
    {
        $clone = clone $this;
        $clone->iterable = $iterable;

        return $clone;
    }

    public function push(callable $modifier): self
    {
        $clone = clone $this;
        $clone->modifiers[] = $modifier;

        return $clone;
    }

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
