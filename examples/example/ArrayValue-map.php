<?php

use GW\Value\Arrays;

$array = Arrays::create(['a', 'b', 'c']);
$mapped = $array->map(function (string $letter): string {
    return 'new ' . $letter;
});

var_export($mapped->toArray());
