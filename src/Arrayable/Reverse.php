<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_reverse;

final class Reverse implements Arrayable
{
    private Arrayable $arrayable;

    public function __construct(Arrayable $arrayable)
    {
        $this->arrayable = $arrayable;
    }

    public function toArray(): array
    {
        return array_reverse($this->arrayable->toArray(), false);
    }
}
