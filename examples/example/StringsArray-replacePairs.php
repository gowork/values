<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['One?', 'Two!', 'Three!?']);

var_export($text->replacePairs(['One' => 'Eleven', 'Two' => 'Twelve'])->toArray());
