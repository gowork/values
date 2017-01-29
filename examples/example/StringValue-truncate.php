<?php

use GW\Value\Strings;

echo Strings::create('It`s Short')->truncate(10)->toString() . PHP_EOL;
echo Strings::create('This one is too long!')->truncate(10)->toString() . PHP_EOL;
echo Strings::create('This one is too long!')->truncate(10, '+')->toString() . PHP_EOL;
