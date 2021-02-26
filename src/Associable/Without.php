<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Associable;
use function array_diff_key;
use function array_flip;

/**
 * @template TKey
 * @template TValue
 * @implements Associable<TKey,TValue>
 */
final class Without implements Associable
{
    /** @var Associable<TKey,TValue> */
    private Associable $associable;
    /** @var TKey[] */
    private array $keys;

    /**
     * @param Associable<TKey,TValue> $associable
     * @param TKey ...$keys
     */
    public function __construct(Associable $associable, ...$keys)
    {
        $this->associable = $associable;
        $this->keys = $keys;
    }

    /** @return array<TKey,TValue> */
    public function toAssocArray(): array
    {
        return array_diff_key($this->associable->toAssocArray(), array_flip($this->keys));
    }
}
