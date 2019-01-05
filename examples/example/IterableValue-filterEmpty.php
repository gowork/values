<?php

use GW\Value\Wrap;

$array = Wrap::iterable(['a', '', 'b', 'c']);
$notEmpty = $array->filterEmpty();

var_export($notEmpty->toArray());
