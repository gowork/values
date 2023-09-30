<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Associable;
use InvalidArgumentException;

/**
 * @template TKey of int|string
 * @template TValue
 * @implements Associable<TKey,TValue>
 */
final class Swap implements Associable
{
    /** @var Associable<TKey,TValue> */
    private Associable $associable;
    /** @var TKey */
    private $keyA;
    /** @var TKey */
    private $keyB;

    /**
     * @param Associable<TKey,TValue> $associable
     * @param TKey $keyA
     * @param TKey $keyB
     */
    public function __construct(Associable $associable, $keyA, $keyB)
    {
        $this->associable = $associable;
        $this->keyA = $keyA;
        $this->keyB = $keyB;
    }

    /** @return array<TKey,TValue> */
    public function toAssocArray(): array
    {
        $items = $this->associable->toAssocArray();
        $valueA = $items[$this->keyA] ?? throw new InvalidArgumentException("Undefined key {$this->keyA}");
        $valueB = $items[$this->keyB] ?? throw new InvalidArgumentException("Undefined key {$this->keyB}");
        $items[$this->keyA] = $valueB;
        $items[$this->keyB] = $valueA;

        return $items;
    }
}
