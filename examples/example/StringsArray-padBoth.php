<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['one', 'two', 'three']);

var_export($text->padBoth(24, '-')->toArray());
