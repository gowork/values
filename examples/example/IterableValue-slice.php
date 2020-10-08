<?php

use GW\Value\Wrap;

$range = function (int $start, int $end): iterable {
    for ($i = $start; $i <= $end; $i++) {
        yield $i;
    }
};

var_export(Wrap::iterable($range(0, PHP_INT_MAX))->slice(2, 4)->toArray());
echo PHP_EOL;

var_export(Wrap::iterable($range(0, PHP_INT_MAX))->slice(-1, 1)->toArray());
echo PHP_EOL;

var_export(Wrap::iterable($range(0, PHP_INT_MAX))->slice(0, 3)->toArray());
echo PHP_EOL;

var_export(Wrap::iterable($range(0, PHP_INT_MAX))->slice(5000, 2)->toArray());
echo PHP_EOL;
