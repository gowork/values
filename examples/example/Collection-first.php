<?php

use GW\Value\Arrays;

$array = Arrays::create(['a', 'b', 'c']);

echo 'array first: ' . $array->first();
echo PHP_EOL;

$assoc = Arrays::assoc(['a' => 1, 'b' => 2, 'c' => 3]);

echo 'assoc first: ' . $assoc->first();
echo PHP_EOL;
