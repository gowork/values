<?php

use GW\Value\Arrays;

$prices = Arrays::create(['a', 'b', 'c', 'd']);

echo $prices->implode(' / ')->toString();
