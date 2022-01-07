<?php

use GW\Value\Wrap;

$numbers = Wrap::numbersArray([1, 3, 5, 10]);

echo "avg(1, 3, 5, 10) = ";
echo $numbers->average()->toNumber();
echo "\n";
