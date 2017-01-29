<?php

use GW\Value\Arrays;

$array = Arrays::create([1, 2, 3, 4]);
$even = $array->filter(function (int $number): bool {
    return $number % 2 === 0;
});

var_export($even->toArray());
