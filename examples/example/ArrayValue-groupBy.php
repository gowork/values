<?php

use GW\Value\ArrayValue;
use GW\Value\Wrap;

$payments = Wrap::array([
    ['group' => 'food', 'amount' => 10],
    ['group' => 'drinks', 'amount' => 10],
    ['group' => 'food', 'amount' => 20],
    ['group' => 'travel', 'amount' => 500],
    ['group' => 'drinks', 'amount' => 20],
    ['group' => 'food', 'amount' => 50],
]);

$get = function (string $key): \Closure {
    return function (array $payment) use ($key) {
        return $payment[$key];
    };
};

echo 'grouped expenses:', PHP_EOL;
var_export(
    $payments
        ->groupBy($get('group'))
        ->map(function (ArrayValue $group) use ($get): array {
            return $group->map($get('amount'))->toArray();
        })
        ->toAssocArray()
);
echo PHP_EOL, PHP_EOL;

$numbers = Wrap::array([1, 2, 3, 4, 3, 4, 5, 6, 7, 8, 9]);
$even = function (int $number): int {
    return $number % 2;
};

echo 'even partition:', PHP_EOL;
var_export(
    $numbers
        ->groupBy($even)
        ->map(function (ArrayValue $group): array {
            return $group->toArray();
        })
        ->toArray()
);
