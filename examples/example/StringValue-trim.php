<?php

use GW\Value\Wrap;

$text = Wrap::string(' :.: I ♡ SPACE :.:  ');

echo $text->trim()->toString() . PHP_EOL;
echo $text->trim(' .:')->toString() . PHP_EOL;
