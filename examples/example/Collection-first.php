<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c']);

echo 'array first: ' . $array->first();
echo PHP_EOL;

$assoc = Wrap::assocArray(['a' => 1, 'b' => 2, 'c' => 3]);

echo 'assoc first: ' . $assoc->first();
echo PHP_EOL;
