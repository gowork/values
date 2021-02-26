<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Associable;

/**
 * @template TKey
 * @template TValue
 * @implements Associable<TKey,TValue>
 */
final class UniqueByComparator implements Associable
{
    /** @var Associable<TKey,TValue> */
    private Associable $associable;
    /** @var callable(TValue,TValue):int */
    private $comparator;

    /**
     * @param Associable<TKey,TValue> $associable
     * @param callable(TValue,TValue):int $comparator
     */
    public function __construct(Associable $associable, callable $comparator)
    {
        $this->associable = $associable;
        $this->comparator = $comparator;
    }

    /** @return array<TKey,TValue> */
    public function toAssocArray(): array
    {
        $result = [];
        foreach ($this->associable->toAssocArray() as $keyA => $valueA) {
            foreach ($result as $valueB) {
                if (($this->comparator)($valueA, $valueB) === 0) {
                    // item already in result
                    continue 2;
                }
            }

            $result[$keyA] = $valueA;
        }

        /** @phpstan-var array<TKey, TValue> $result */
        return $result;
    }
}
