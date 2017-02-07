<?php

use GW\Value\Wrap;

$one = Wrap::array(['a', 'b', 'c']);
$two = Wrap::array(['d', 'e', 'f']);

var_export($one->join($two)->toArray());
