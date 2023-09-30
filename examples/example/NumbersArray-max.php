<?php

use GW\Value\Wrap;

$numbers = Wrap::numbersArray([100, 10, 50, 80]);

echo "max(100, 10, 50, 80) = ";
echo $numbers->max()->toNumber();
echo "\n";
