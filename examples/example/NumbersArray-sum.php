<?php

use GW\Value\Wrap;

$numbers = Wrap::numbersArray([1, 2.5, 5, 10]);

echo "1 + 2.5 + 5 + 10 = ";
echo $numbers->sum()->toNumber();
echo "\n";
