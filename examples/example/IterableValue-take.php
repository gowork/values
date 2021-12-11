<?php

use GW\Value\Wrap;

$range = function (int $start, int $end): iterable {
    for ($i = $start; $i <= $end; $i++) {
        yield $i;
    }
};

var_export(Wrap::iterable($range(0, PHP_INT_MAX))->skip(2)->take(4)->toArray());
echo PHP_EOL;

var_export(Wrap::iterable($range(0, PHP_INT_MAX))->take(3)->toArray());
echo PHP_EOL;

var_export(Wrap::iterable($range(0, PHP_INT_MAX))->skip(5000)->take(2)->toArray());
echo PHP_EOL;
