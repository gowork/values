<?php

use GW\Value\Wrap;

$range = function (int $start, int $end) {
    for ($i = $start; $i <= $end; $i++) {
        yield $i;
    }
};

$array = Wrap::iterable($range(1, PHP_INT_MAX));

echo 'first: ' . $array->first();
