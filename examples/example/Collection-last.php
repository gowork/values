<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c']);

echo 'array last: ' . $array->last();
echo PHP_EOL;

$assoc = Wrap::assocArray(['a' => 1, 'b' => 2, 'c' => 3]);

echo 'assoc last: ' . $assoc->last();
echo PHP_EOL;
