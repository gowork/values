<?php

use GW\Value\Arrays;

$words = Arrays::create(['a', 'b', 'c']);

var_export($words->shift($x)->toArray());
echo PHP_EOL;
echo 'x: ' . $x;

