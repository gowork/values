<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_unique;
use function array_values;

/**
 * @template TValue
 * @implements Arrayable<TValue>
 */
final class UniqueByString implements Arrayable
{
    /** @var Arrayable<TValue> */
    private Arrayable $arrayable;

    /** @param Arrayable<TValue> $arrayable */
    public function __construct(Arrayable $arrayable)
    {
        $this->arrayable = $arrayable;
    }

    /** @return TValue[] */
    public function toArray(): array
    {
        return array_values(array_unique($this->arrayable->toArray()));
    }
}
