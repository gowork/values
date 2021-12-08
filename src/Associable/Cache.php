<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Associable;

/**
 * @template TKey of int|string
 * @template TValue
 * @implements Associable<TKey,TValue>
 */
final class Cache implements Associable
{
    /** @var Associable<TKey,TValue>|null */
    private ?Associable $arrayable;
    /** @var array<TKey,TValue> */
    private array $array = [];

    /** @param Associable<TKey,TValue> $arrayable */
    public function __construct(Associable $arrayable)
    {
        $this->arrayable = $arrayable;
    }

    /** @return array<TKey,TValue> */
    public function toAssocArray(): array
    {
        if ($this->arrayable !== null) {
            $this->array = $this->arrayable->toAssocArray();
            $this->arrayable = null;
        }

        return $this->array;
    }
}
