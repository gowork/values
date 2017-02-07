<?php

use GW\Value\Wrap;

$text = Wrap::string('My favourite color is pink!');

echo $text->replace('pink', 'blue')->toString();
