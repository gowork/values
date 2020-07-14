<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c', 'd', 'e', 'f', 'g']);

var_export(
    $array->toAssocValue()
        ->mapKeys(function (int $oldKey, string $value): string {
            return "{$oldKey}:{$value}";
        })
        ->toAssocArray()
);

