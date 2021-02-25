<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_map;

/**
 * @template TValue
 * @template TNewValue
 * @implements Arrayable<TNewValue>
 */
final class Map implements Arrayable
{
    /** @var Arrayable<TValue> */
    private Arrayable $arrayable;
    /** @var callable(TValue):TNewValue */
    private $callback;

    /**
     * @param Arrayable<TValue> $arrayable
     * @param callable(TValue):TNewValue $callback
     */
    public function __construct(Arrayable $arrayable, callable $callback)
    {
        $this->arrayable = $arrayable;
        $this->callback = $callback;
    }

    /** @return TNewValue[] */
    public function toArray(): array
    {
        return array_map($this->callback, $this->arrayable->toArray());
    }
}
