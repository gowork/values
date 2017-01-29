<?php

use GW\Value\Arrays;
use GW\Value\Strings;

echo "['a']: ";
var_export(Arrays::create(['a'])->isEmpty());
echo PHP_EOL;

echo "[]: ";
var_export(Arrays::create([])->isEmpty());
echo PHP_EOL;

echo "'a': ";
var_export(Strings::create('a')->isEmpty());
echo PHP_EOL;

echo "'': ";
var_export(Strings::create('')->isEmpty());
echo PHP_EOL;
