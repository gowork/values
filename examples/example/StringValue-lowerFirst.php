<?php

use GW\Value\Strings;

$text = Strings::create('CamelCaseMethod()');

echo $text->lowerFirst()->toString() . PHP_EOL;
