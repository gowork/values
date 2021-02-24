<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_unique;
use function array_values;

final class UniqueByString implements Arrayable
{
    private Arrayable $arrayable;

    public function __construct(Arrayable $arrayable)
    {
        $this->arrayable = $arrayable;
    }

    public function toArray(): array
    {
        return array_values(array_unique($this->arrayable->toArray()));
    }
}
