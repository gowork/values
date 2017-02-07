<?php

use GW\Value\Wrap;

$array = Wrap::array([1, 2, 3, 4]);
$even = $array->filter(function (int $number): bool {
    return $number % 2 === 0;
});

var_export($even->toArray());
