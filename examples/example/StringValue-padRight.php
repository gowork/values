<?php

use GW\Value\Wrap;

$text = Wrap::string('cut here ☞');

echo $text->padRight(16, '-')->toString();
