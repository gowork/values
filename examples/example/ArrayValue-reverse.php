<?php

use GW\Value\Arrays;

$words = Arrays::create(['do', 'or', 'do', 'not', 'there', 'is', 'no', 'try']);

echo $words->reverse()->implode(' ')->toString();

