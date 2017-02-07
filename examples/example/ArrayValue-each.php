<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c']);
$mapped = $array->each(function (string $letter): void {
    echo $letter;
});
