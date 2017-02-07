<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['one', 'two', 'three']);

var_export($text->padRight(16, '-')->toArray());
