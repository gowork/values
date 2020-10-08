<?php

namespace GW\Value;

use function get_class;
use function is_object;

final class Filters
{
    private const TYPE_FILTER_CALLABLE = [
        'int' => 'is_int',
        'integer' => 'is_int',
        'float' => 'is_float',
        'string' => 'is_string',
        'object' => 'is_object',
        'scalar' => 'is_scalar',
        'array' => 'is_array',
        'bool' => 'is_bool',
        'boolean' => 'is_bool',
    ];

    /**
     * @return callable(mixed $value): bool
     */
    public static function type(string $type): callable
    {
        return static function ($value) use ($type): bool {
            $callable = self::TYPE_FILTER_CALLABLE[$type] ?? null;

            if ($callable !== null) {
                return $callable($value);
            }

            if (is_object($value)) {
                return get_class($value) === $type;
            }

            return false;
        };
    }

    /**
     * @return callable(mixed $value): bool
     */
    public static function types(string ...$types): callable
    {
        $callbacks = Wrap::array($types)->map([self::class, 'type'])->toArray();

        return static function ($value) use ($callbacks): bool {
            foreach ($callbacks as $callback) {
                if ($callback($value) === true) {
                    return true;
                }
            }

            return false;
        };
    }

    /**
     * @return callable(mixed $value): bool
     */
    public static function notType(string $type): callable
    {
        return self::not(self::type($type));
    }

    /**
     * @return callable(mixed $value): bool
     */
    public static function notTypes(string ...$types): callable
    {
        return self::not(self::types(...$types));
    }

    /**
     * @return callable(mixed $value): bool
     */
    public static function not(callable $filter): callable
    {
        return static fn($value): bool => !$filter($value);
    }

    /**
     * @return callable(mixed $value): bool
     */
    public static function notEmpty(): callable
    {
        return static fn($value): bool => !(($value instanceof Value && $value->isEmpty()) || empty($value));
    }

    /**
     * @param mixed $other
     * @return callable(mixed $value): bool
     */
    public static function equal($other): callable
    {
        return static fn($value): bool => $value === $other;
    }

    /**
     * @param mixed $other
     * @return callable(mixed $value): bool
     */
    public static function notEqual($other): callable
    {
        return static fn($value): bool => $value !== $other;
    }

    /**
     * @param array<int, mixed> $args
     * @return callable(object $item): bool
     */
    public static function callMethod(string $method, ...$args): callable
    {
        return static fn(object $item): bool => $item->$method(...$args);
    }
}
