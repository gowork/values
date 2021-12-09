<?php

use GW\Value\Wrap;

$letters = Wrap::array(['a', 'b', 'c', 'd', 'e', 'f', 'g']);

var_export($letters->skip(2)->toArray());
echo PHP_EOL;
