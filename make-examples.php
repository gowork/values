<?php

use doc\GW\Value\ReadmeWriter;
use GW\Value\ArrayValue;
use GW\Value\AssocValue;
use GW\Value\IterableValue;
use GW\Value\NumbersArray;
use GW\Value\NumberValue;
use GW\Value\StringsArray;
use GW\Value\StringValue;

require __DIR__ . '/vendor/autoload.php';

$markdown = (new ReadmeWriter())->describeClasses([
    ArrayValue::class,
    AssocValue::class,
    StringValue::class,
    StringsArray::class,
    IterableValue::class,
    NumberValue::class,
    NumbersArray::class,
]);

file_put_contents('docs/examples.md', $markdown->toString());
