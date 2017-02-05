<?php

use GW\Value\Strings;

$text = Strings::create('My favourite color is pink!');

echo $text->replace('pink', 'blue')->toString();
