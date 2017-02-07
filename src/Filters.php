<?php

namespace GW\Value;

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

    public static function type(string $type): \Closure
    {
        return function ($value) use ($type): bool {
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

    public static function types(string ...$types): \Closure
    {
        $callbacks = Wrap::array($types)->map([self::class, 'type'])->toArray();

        return function ($value) use ($callbacks): bool {
            foreach ($callbacks as $callback) {
                if ($callback($value) === true) {
                    return true;
                }
            }

            return false;
        };
    }

    public static function notType(string $type): \Closure
    {
        return self::not(self::type($type));
    }

    public static function notTypes(string ...$types): \Closure
    {
        return self::not(self::types($types));
    }

    public static function not(callable $filter): \Closure
    {
        return function ($value) use ($filter): bool {
            return !$filter($value);
        };
    }

    public static function notEmpty(): \Closure
    {
        return function ($value): bool {
            return !(($value instanceof Value && $value->isEmpty()) || empty($value));
        };
    }

    public static function equal($other): \Closure
    {
        return function ($value) use ($other): bool {
            return $value === $other;
        };
    }

    public static function notEqual($other): \Closure
    {
        return function ($value) use ($other): bool {
            return $value !== $other;
        };
    }

    public static function callMethod(string $method, ...$args): \Closure
    {
        return function ($item) use ($method, $args): bool {
            return $item->$method(...$args);
        };
    }
}
