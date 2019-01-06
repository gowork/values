<?php

use GW\Value\Wrap;

$range = function (int $start, int $end) {
    for ($i = $start; $i <= $end; $i++) {
        yield $i;
    }
};

$one = Wrap::iterable($range(1, 3));
$two = Wrap::iterable($range(8, 10));

var_export($one->join($two)->join($range(11, 14))->toArray());
