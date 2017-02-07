<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['SOMETIMES', 'I', 'WANNA', 'SCREAM!']);

var_export($text->lower()->toArray());
