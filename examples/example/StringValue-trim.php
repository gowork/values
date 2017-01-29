<?php

use GW\Value\Strings;

$text = Strings::create(' :.: I ♡ SPACE :.:  ');

echo $text->trim()->toString() . PHP_EOL;
echo $text->trim(' .:')->toString() . PHP_EOL;
