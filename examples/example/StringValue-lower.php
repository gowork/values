<?php

use GW\Value\Strings;

$text = Strings::create('SOMETIMES I WANNA SCREAM!');

echo $text->lower()->toString() . PHP_EOL;
