<?php

use GW\Value\Strings;

$text = Strings::create('cut here ☞');

echo $text->padRight(16, '-')->toString();
