<?php

use GW\Value\Wrap;

$words = Wrap::array(['a', 'b', 'c']);

var_export($words->unshift('X')->toArray());

