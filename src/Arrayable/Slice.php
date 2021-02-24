<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_slice;

final class Slice implements Arrayable
{
    private Arrayable $arrayable;
    private int $offset;
    private int $length;

    public function __construct(Arrayable $arrayable, int $offset, int $length)
    {
        $this->arrayable = $arrayable;
        $this->offset = $offset;
        $this->length = $length;
    }

    public function toArray(): array
    {
        return array_slice($this->arrayable->toArray(), $this->offset, $this->length);
    }
}
