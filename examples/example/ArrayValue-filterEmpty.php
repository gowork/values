<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', '', 'b', 'c']);
$notEmpty = $array->filterEmpty();

var_export($notEmpty->toArray());
