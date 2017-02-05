<?php

use GW\Value\Arrays;

$array = Arrays::create(['a', '', 'b', 'c']);
$notEmpty = $array->filterEmpty();

var_export($notEmpty->toArray());
