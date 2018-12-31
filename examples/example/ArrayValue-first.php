<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c']);

echo 'array first: ' . $array->first();
