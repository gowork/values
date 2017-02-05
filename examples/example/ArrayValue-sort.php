<?php

use GW\Value\Arrays;
use GW\Value\Sorts;

$array = Arrays::create(['c', 'a', 'b']);
$customSort = $array->sort(function (string $a, string $b): int {
    return $a <=> $b;
});

$ascending = $array->sort(Sorts::asc());
$descending = $array->sort(Sorts::desc());

echo 'customSort = ';
var_export($customSort->toArray());
echo PHP_EOL;

echo 'ascending = ';
var_export($ascending->toArray());
echo PHP_EOL;

echo 'descending = ';
var_export($descending->toArray());
