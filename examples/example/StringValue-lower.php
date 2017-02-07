<?php

use GW\Value\Wrap;

$text = Wrap::string('SOMETIMES I WANNA SCREAM!');

echo $text->lower()->toString() . PHP_EOL;
