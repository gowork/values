<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_reverse;

/**
 * @template TValue
 * @implements Arrayable<TValue>
 */
final class Reverse implements Arrayable
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
        return array_reverse($this->arrayable->toArray(), false);
    }
}
