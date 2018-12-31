<?php

use GW\Value\Wrap;

echo "['a']: ";
var_export(Wrap::array(['a'])->isEmpty());
echo PHP_EOL;

echo "[]: ";
var_export(Wrap::array([])->isEmpty());
echo PHP_EOL;
