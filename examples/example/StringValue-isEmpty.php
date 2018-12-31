<?php

use GW\Value\Wrap;

echo "'a': ";
var_export(Wrap::string('a')->isEmpty());
echo PHP_EOL;

echo "'': ";
var_export(Wrap::string('')->isEmpty());
echo PHP_EOL;
