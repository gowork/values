<?php

use GW\Value\Wrap;

$letters = Wrap::array(['a', 'b', 'c', 'd', 'e', 'f', 'g']);

var_export($letters->skip(2)->take(4)->toArray());
echo PHP_EOL;

var_export($letters->take(3)->toArray());
echo PHP_EOL;

var_export($letters->take(100)->toArray());
echo PHP_EOL;

