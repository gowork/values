<?php

use GW\Value\Arrays;

$array = Arrays::create(['a', 'b', 'c']);

echo 'array last: ' . $array->last();
echo PHP_EOL;

$assoc = Arrays::assoc(['a' => 1, 'b' => 2, 'c' => 3]);

echo 'assoc last: ' . $assoc->last();
echo PHP_EOL;
