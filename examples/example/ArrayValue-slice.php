<?php

use GW\Value\Wrap;

$letters = Wrap::array(['a', 'b', 'c', 'd', 'e', 'f', 'g']);

var_export($letters->slice(2, 4)->toArray());
echo PHP_EOL;

var_export($letters->slice(-1, 1)->toArray());
echo PHP_EOL;

var_export($letters->slice(0, 3)->toArray());
echo PHP_EOL;

var_export($letters->slice(0, 100)->toArray());
echo PHP_EOL;

