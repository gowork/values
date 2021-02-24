<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_chunk;

final class Chunk implements Arrayable
{
    private Arrayable $arrayable;
    private int $size;

    public function __construct(Arrayable $arrayable, int $size)
    {
        $this->arrayable = $arrayable;
        $this->size = $size;
    }

    public function toArray(): array
    {
        return array_chunk($this->arrayable->toArray(), $this->size, false);
    }
}
