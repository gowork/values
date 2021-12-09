<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;

/**
 * @template TValue
 * @implements Arrayable<TValue>
 */
final class Cache implements Arrayable
{
    /** @var Arrayable<TValue>|null */
    private ?Arrayable $arrayable;
    /** @var TValue[] */
    private array $array = [];

    /** @param Arrayable<TValue> $arrayable */
    public function __construct(Arrayable $arrayable)
    {
        $this->arrayable = $arrayable;
    }

    /** @return TValue[] */
    public function toArray(): array
    {
        if ($this->arrayable !== null) {
            $this->array = $this->arrayable->toArray();
            $this->arrayable = null;
        }

        return $this->array;
    }
}
