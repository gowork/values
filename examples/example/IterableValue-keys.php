<?php

use GW\Value\Wrap;

$assoc = Wrap::iterable(['0' => 'zero', '1' => 'one']);

$keys = $assoc
    ->map(fn(string $val, int $key): string => $val)
    ->keys()
    ->toArray();

var_export($keys);

$pairs = [['0', 'zero'], ['1', 'one'], ['1', 'one one']];

$iterator = function () use ($pairs) {
    foreach ($pairs as [$key, $item]) {
        yield $key => $item;
    }
};

$assoc = Wrap::iterable($iterator());

$keys = $assoc
    ->map(fn(string $val, string $key): string => $val)
    ->keys()
    ->toArray();

var_export($keys);
