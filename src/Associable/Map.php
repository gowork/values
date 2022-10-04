<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Associable;

/**
 * @template TKey of int|string
 * @template TValue
 * @template TNewValue
 * @implements Associable<TKey,TNewValue>
 */
final class Map implements Associable
{
    /** @var Associable<TKey,TValue> */
    private Associable $associable;
    /** @var callable(TValue $value, TKey $key):TNewValue */
    private $transformer;

    /**
     * @param Associable<TKey,TValue> $associable
     * @param callable(TValue $value, TKey $key):TNewValue $transformer
     */
    public function __construct(Associable $associable, callable $transformer)
    {
        $this->associable  = $associable;
        $this->transformer = $transformer;
    }

    public function toAssocArray(): array
    {
        $result = [];
        foreach ($this->associable->toAssocArray() as $key => $value) {
            $result[$key] = ($this->transformer)($value, $key);
        }

        return $result;
    }
}
