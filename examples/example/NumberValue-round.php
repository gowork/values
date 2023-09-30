<?php

use GW\Value\Wrap;

echo "round(22.55, 1) = ";
echo Wrap::number(22.55)->round(1)->toNumber();
echo "\n";
