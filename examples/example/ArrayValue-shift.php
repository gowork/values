<?php

use GW\Value\Wrap;

$words = Wrap::array(['a', 'b', 'c']);

var_export($words->shift($x)->toArray());
echo PHP_EOL;
echo 'x: ' . $x;

