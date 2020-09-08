<?php

use GW\Value\Wrap;

$range = static function (int $start, int $end): iterable {
    for ($i = $start; $i <= $end; $i++) {
        yield $i;
    }
};

$array = Wrap::iterable($range(1, 4));
$even = $array->filter(function (int $number): bool {
    return $number % 2 === 0;
});

var_export($even->toArray());
