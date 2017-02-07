<?php

use GW\Value\Wrap;

$prices = Wrap::array(['a', 'b', 'c', 'd']);

echo $prices->implode(' / ')->toString();
