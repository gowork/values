<?php declare(strict_types=1);

namespace GW\Value;

/**
 * @internal
 */
final class IterableValueStack
{
    private IterableValueIterator $iterable;

    /** @var callable[] */
    private array $modifiers = [];

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

    private function next(array &$modifiers, iterable &$iterable, int $i): iterable
    {
        if (!isset($modifiers[$i])) {
            yield from $iterable;

            return;
        }

        yield from $modifiers[$i--]($this->next($modifiers, $iterable, $i));
    }

    public function iterate(): iterable
    {
        $iterable = ($this->iterable)();
        $modifiers = $this->modifiers;
        $i = \count($modifiers) - 1;

        yield from $this->next($modifiers, $iterable, $i);
    }
}
