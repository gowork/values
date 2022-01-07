<?php

use GW\Value\Wrap;

$numbers = Wrap::numbersArray([100, 10, 50, 80]);

echo "min(100, 10, 50, 80) = ";
echo $numbers->min()->toNumber();
echo "\n";
