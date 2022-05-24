<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_chunk;

/**
 * @template TValue
 * @implements Arrayable<array<int,TValue>>
 */
final class Chunk implements Arrayable
{
    /** @var Arrayable<TValue> */
    private Arrayable $arrayable;
    /** @var int<1, max> */
    private int $size;

    /**
     * @param Arrayable<TValue> $arrayable
     * @param int<1, max> $size
     */
    public function __construct(Arrayable $arrayable, int $size)
    {
        $this->arrayable = $arrayable;
        $this->size = $size;
    }

    /** @return array<int,TValue>[] */
    public function toArray(): array
    {
        return array_chunk($this->arrayable->toArray(), $this->size, false);
    }
}
