<?php

use GW\Value\Wrap;

$prices = Wrap::iterable([10, 20, 50, 120]);

$summarize = function(int $sum, int $price): int {
    return $sum + $price;
};

echo 'Sum: ' . $prices->reduce($summarize, 0);
echo PHP_EOL;

$list = function(string $list, int $price): string {
    return $list . " €{$price},-";
};

echo 'Prices: ' . $prices->reduce($list, '');
echo PHP_EOL;
