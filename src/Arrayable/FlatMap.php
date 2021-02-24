<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_merge;
use function is_array;

final class FlatMap implements Arrayable
{
    private Arrayable $items;
    /** @var callable */
    private $transformer;

    public function __construct(Arrayable $items, callable $transformer)
    {
        $this->items = $items;
        $this->transformer = $transformer;
    }

    public function toArray(): array
    {
        $elements = [];

        foreach ($this->items->toArray() as $item) {
            $transformed = ($this->transformer)($item);
            $elements[] = is_array($transformed) ? $transformed : [...$transformed];
        }

        return array_merge([], ...$elements);
    }
}
