<?php

use GW\Value\Strings;

$text = Strings::create('You are looking good! Really!');

echo $text->replacePattern('/[aeiouy]/', '')->toString();
