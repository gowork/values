<?php

use GW\Value\Wrap;

echo Wrap::string('It`s Short')->truncate(10)->toString() . PHP_EOL;
echo Wrap::string('This one is too long!')->truncate(10)->toString() . PHP_EOL;
echo Wrap::string('This one is too long!')->truncate(10, '+')->toString() . PHP_EOL;
