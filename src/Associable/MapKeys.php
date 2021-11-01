<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Associable;

/**
 * @template TKey of int|string
 * @template TNewKey of int|string
 * @template TValue
 * @implements Associable<TNewKey,TValue>
 */
final class MapKeys implements Associable
{
    /** @var Associable<TKey,TValue> */
    private Associable $associable;
    /** @var callable(TKey $key, TValue $value=):TNewKey */
    private $transformer;

    /**
     * @param Associable<TKey,TValue> $associable
     * @param callable(TKey $key, TValue $value=):TNewKey $transformer
     */
    public function __construct(Associable $associable, callable $transformer)
    {
        $this->associable  = $associable;
        $this->transformer = $transformer;
    }

    /** @return array<TNewKey,TValue> */
    public function toAssocArray(): array
    {
        $transformed = [];
        foreach ($this->associable->toAssocArray() as $oldKey => $value) {
            $transformed[($this->transformer)($oldKey, $value)] = $value;
        }

        return $transformed;
    }
}
