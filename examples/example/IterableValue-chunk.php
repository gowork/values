<?php

use GW\Value\Wrap;

$array = Wrap::iterable([1, 2, 3, 4, 3, 4, 5, 6, 7, 8, 9]);

var_export($array->chunk(3)->toArray());
