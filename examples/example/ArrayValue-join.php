<?php

use GW\Value\Arrays;

$one = Arrays::create(['a', 'b', 'c']);
$two = Arrays::create(['d', 'e', 'f']);

var_export($one->join($two)->toArray());
