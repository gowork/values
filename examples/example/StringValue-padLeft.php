<?php

use GW\Value\Strings;

$text = Strings::create('☜ cut here');

echo $text->padLeft(16, '-')->toString();
