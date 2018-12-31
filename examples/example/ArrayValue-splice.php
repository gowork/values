<?php

use GW\Value\Wrap;

$letters = Wrap::array(['a', 'b', 'c', 'd', 'e', 'f', 'g']);

var_export($letters->splice(2, 4)->toArray());
echo PHP_EOL;

var_export($letters->splice(2, 4, Wrap::array(['x', 'y', 'z']))->toArray());
echo PHP_EOL;

var_export($letters->splice(-1, 1)->toArray());
echo PHP_EOL;

var_export($letters->splice(-1, 1, Wrap::array(['x', 'y']))->toArray());
echo PHP_EOL;

var_export($letters->splice(0, 3)->toArray());
echo PHP_EOL;

var_export($letters->splice(0, 100)->toArray());
echo PHP_EOL;
