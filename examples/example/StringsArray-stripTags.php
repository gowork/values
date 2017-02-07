<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['<h1>Story</h1>', '<h2>Chapter 1</h2>', '<p>Once upon a time...</p>']);

var_export($text->stripTags()->toArray());
