<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_merge;
use function is_array;

/**
 * @template TValue
 * @template TNewValue
 * @implements Arrayable<TNewValue>
 */
final class FlatMap implements Arrayable
{
    /** @var Arrayable<TValue> */
    private Arrayable $arrayable;
    /** @var callable(TValue):iterable<TNewValue> */
    private $transformer;

    /**
     * @param Arrayable<TValue> $arrayable
     * @param callable(TValue):iterable<TNewValue> $transformer
     */
    public function __construct(Arrayable $arrayable, callable $transformer)
    {
        $this->arrayable = $arrayable;
        $this->transformer = $transformer;
    }

    /** @return TNewValue[] */
    public function toArray(): array
    {
        $elements = [];

        foreach ($this->arrayable->toArray() as $item) {
            $transformed = ($this->transformer)($item);
            $elements[] = is_array($transformed) ? $transformed : [...$transformed];
        }

        return array_merge([], ...$elements);
    }
}
