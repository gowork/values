<?php

use GW\Value\Arrays;

$array = Arrays::create(['a', 'b', 'c']);
$mapped = $array->each(function (string $letter): void {
    echo $letter;
});
