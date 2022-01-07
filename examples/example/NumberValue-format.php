<?php

use GW\Value\Wrap;

$number = Wrap::number(1000.111111111);

echo $number->format(2)->toString();
echo "\n";

echo $number->format(3, '.', ' ')->toString();
echo "\n";
