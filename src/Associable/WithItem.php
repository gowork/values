<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Associable;
use function array_reverse;

/**
 * @template TKey of int|string
 * @template TValue
 * @implements Associable<TKey,TValue>
 */
final class WithItem implements Associable
{
    /** @var Associable<TKey,TValue> */
    private Associable $associable;
    /** @var TKey */
    private $key;
    /** @var TValue */
    private $value;

    /**
     * @param Associable<TKey,TValue> $associable
     * @param TKey $key
     * @param TValue $value
     */
    public function __construct(Associable $associable, $key, $value)
    {
        $this->associable = $associable;
        $this->key = $key;
        $this->value = $value;
    }

    /** @return array<TKey,TValue> */
    public function toAssocArray(): array
    {
        $items = $this->associable->toAssocArray();
        $items[$this->key] = $this->value;

        return $items;
    }
}
