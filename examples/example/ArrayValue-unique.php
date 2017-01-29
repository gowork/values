<?php

use GW\Value\Arrays;

$names = Arrays::create(['John', 'Basil', 'John', 'Johny', 'Jon', 'Basile']);

echo 'unique names = ';
var_export($names->unique()->toArray());
echo PHP_EOL;

echo 'unique by callback = ';
var_export($names->unique(function(string $nameA, string $nameB): int {
    return levenshtein($nameA, $nameB) > 2 ? 1 : 0;
})->toArray());
echo PHP_EOL;
