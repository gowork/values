<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['short', 'quite long', 'very very long']);

var_export($text->truncate(5, '~~')->toArray());
