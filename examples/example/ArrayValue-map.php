<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c']);
$mapped = $array->map(function (string $letter): string {
    return 'new ' . $letter;
});

var_export($mapped->toArray());
