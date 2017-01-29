`ArrayValue` defines interface of value object for indexed array.

#### Examples

```php
<?php

$array = \GW\Value\Arrays::create(['a', 'b', 'c']);

$array[0]; // 'a'
$has = isset($array[3]); // false
$array[3]; // Exception
$array->count(); // 3

foreach ($array as $item) {
    echo $item;
} // abc

```
