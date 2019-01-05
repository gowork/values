<?php

use GW\Value\Wrap;

$array = Wrap::iterable(['a', 'b', 'c']);
$mapped = $array->each(function (string $letter): void {
    echo $letter;
});
