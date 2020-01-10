<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['One?', 'Two!', 'Three!?']);

var_export($text->replaceAll(['?', '!'], '.')->toArray());
