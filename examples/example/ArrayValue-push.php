<?php

use GW\Value\Arrays;

$words = Arrays::create(['a', 'b', 'c']);

var_export($words->push('X')->toArray());

