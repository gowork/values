# PHP Value Objects

## ArrayValue

`ArrayValue` defines interface of value object for indexed array.

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c']);

$array[0]; // 'a'
$has = isset($array[3]); // false
$array[3]; // Exception
$array->count(); // 3

foreach ($array as $item) {
    echo $item;
} // abc

```


### ArrayValue::each

```php
<?php
/**
 * @param callable $callback function(mixed $value): void
 * @return ArrayValue
 */
public function each(callable $callback);
```

Call some callback on each item of `ArrayValue` and return this `ArrayValue`. 

Items are not reassigned, so state `ArrayValue` should not have changed. 

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c']);
$mapped = $array->each(function (string $letter): void {
    echo $letter;
});
```

```
abc
```

### ArrayValue::unique

```php
<?php
/**
 * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
 * @return ArrayValue
 */
public function unique(?callable $comparator = null);
```

Filter `ArrayValue` items removing duplicated items.

When `$comparator` is not provided items are compared as strings.

#### Examples

```php
<?php

use GW\Value\Wrap;

$names = Wrap::array(['John', 'Basil', 'John', 'Johny', 'Jon', 'Basile']);

echo 'unique names = ';
var_export($names->unique()->toArray());
echo PHP_EOL;

echo 'unique by callback = ';
var_export($names->unique(function(string $nameA, string $nameB): int {
    return levenshtein($nameA, $nameB) > 2 ? 1 : 0;
})->toArray());
echo PHP_EOL;
```

```
unique names = array (
  0 => 'John',
  1 => 'Basil',
  2 => 'Johny',
  3 => 'Jon',
  4 => 'Basile',
)
unique by callback = array (
  0 => 'John',
  1 => 'Basil',
)
```

### ArrayValue::toArray

```php
<?php
/**
 * @return mixed[]
 */
public function toArray(): array;
```

Return primitive `array` from subject `ArrayValue`.

### ArrayValue::filter

```php
<?php
/**
 * @param callable $transformer function(mixed $value): bool { ... }
 * @return ArrayValue
 */
public function filter(callable $transformer);
```

Create new `ArrayValue` with items filtered by callback.

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::array([1, 2, 3, 4]);
$even = $array->filter(function (int $number): bool {
    return $number % 2 === 0;
});

var_export($even->toArray());
```

```
array (
  0 => 2,
  1 => 4,
)
```

### ArrayValue::filterEmpty

```php
<?php
/**
 * @return ArrayValue
 */
public function filterEmpty();
```

Filter out empty items from `ArrayValue`.

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', '', 'b', 'c']);
$notEmpty = $array->filterEmpty();

var_export($notEmpty->toArray());
```

```
array (
  0 => 'a',
  1 => 'b',
  2 => 'c',
)
```

### ArrayValue::map

```php
<?php
/**
 * @param callable $transformer function(mixed $value): mixed { ... }
 * @return ArrayValue
 */
public function map(callable $transformer);
```

Create new `ArrayValue` with items mapped by callback.

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c']);
$mapped = $array->map(function (string $letter): string {
    return 'new ' . $letter;
});

var_export($mapped->toArray());
```

```
array (
  0 => 'new a',
  1 => 'new b',
  2 => 'new c',
)
```

### ArrayValue::sort

```php
<?php
/**
 * @param callable $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
 * @return ArrayValue
 */
public function sort(callable $comparator);
```

Create new `ArrayValue` with items sorted by callback.

#### Examples

```php
<?php

use GW\Value\Wrap;
use GW\Value\Sorts;

$array = Wrap::array(['c', 'a', 'b']);
$customSort = $array->sort(function (string $a, string $b): int {
    return $a <=> $b;
});

$ascending = $array->sort(Sorts::asc());
$descending = $array->sort(Sorts::desc());

echo 'customSort = ';
var_export($customSort->toArray());
echo PHP_EOL;

echo 'ascending = ';
var_export($ascending->toArray());
echo PHP_EOL;

echo 'descending = ';
var_export($descending->toArray());
```

```
customSort = array (
  0 => 'a',
  1 => 'b',
  2 => 'c',
)
ascending = array (
  0 => 'a',
  1 => 'b',
  2 => 'c',
)
descending = array (
  0 => 'c',
  1 => 'b',
  2 => 'a',
)
```

### ArrayValue::shuffle

```php
<?php
/**
 * @return ArrayValue
 */
public function shuffle();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$words = Wrap::array(['do', 'or', 'do', 'not', 'there', 'is', 'no', 'try']);

echo $words->shuffle()->implode(' ')->toString();
```

```
there try do do or no is not
```

### ArrayValue::reverse

```php
<?php
/**
 * @return ArrayValue
 */
public function reverse();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$words = Wrap::array(['do', 'or', 'do', 'not', 'there', 'is', 'no', 'try']);

echo $words->reverse()->implode(' ')->toString();
```

```
try no is there not do or do
```

### ArrayValue::unshift

```php
<?php
/**
 * @param mixed $value
 * @return ArrayValue
 */
public function unshift($value);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$words = Wrap::array(['a', 'b', 'c']);

var_export($words->unshift('X')->toArray());
```

```
array (
  0 => 'X',
  1 => 'a',
  2 => 'b',
  3 => 'c',
)
```

### ArrayValue::shift

```php
<?php
/**
 * @param mixed $value
 * @return ArrayValue
 */
public function shift(&$value = null);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$words = Wrap::array(['a', 'b', 'c']);

var_export($words->shift($x)->toArray());
echo PHP_EOL;
echo 'x: ' . $x;
```

```
array (
  0 => 'b',
  1 => 'c',
)
x: a
```

### ArrayValue::push

```php
<?php
/**
 * @param mixed $value
 * @return ArrayValue
 */
public function push($value);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$words = Wrap::array(['a', 'b', 'c']);

var_export($words->push('X')->toArray());
```

```
array (
  0 => 'a',
  1 => 'b',
  2 => 'c',
  3 => 'X',
)
```

### ArrayValue::pop

```php
<?php
/**
 * @param mixed $value
 * @return ArrayValue
 */
public function pop(&$value = null);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$words = Wrap::array(['a', 'b', 'c']);

var_export($words->pop($x)->toArray());
echo PHP_EOL;
echo 'x: ' . $x;
```

```
array (
  0 => 'a',
  1 => 'b',
)
x: c
```

### ArrayValue::offsetExists

```php
<?php
/**
 * @param int $offset
 */
public function offsetExists($offset): bool;
```

### ArrayValue::offsetGet

```php
<?php
/**
 * @param int $offset
 * @return mixed
 */
public function offsetGet($offset);
```

### ArrayValue::offsetSet

```php
<?php
/**
 * @param int $offset
 * @param mixed $value
 * @return void
 * @throws \BadMethodCallException For immutable types.
 */
public function offsetSet($offset, $value);
```

### ArrayValue::offsetUnset

```php
<?php
/**
 * @param int $offset
 * @return void
 * @throws \BadMethodCallException For immutable types.
 */
public function offsetUnset($offset);
```

### ArrayValue::join

```php
<?php
/**
 * @return ArrayValue
 */
public function join(ArrayValue $other);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$one = Wrap::array(['a', 'b', 'c']);
$two = Wrap::array(['d', 'e', 'f']);

var_export($one->join($two)->toArray());
```

```
array (
  0 => 'a',
  1 => 'b',
  2 => 'c',
  3 => 'd',
  4 => 'e',
  5 => 'f',
)
```

### ArrayValue::slice

```php
<?php
/**
 * @return ArrayValue
 */
public function slice(int $offset, int $length);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$letters = Wrap::array(['a', 'b', 'c', 'd', 'e', 'f', 'g']);

var_export($letters->slice(2, 4)->toArray());
echo PHP_EOL;

var_export($letters->slice(-1, 1)->toArray());
echo PHP_EOL;

var_export($letters->slice(0, 3)->toArray());
echo PHP_EOL;

var_export($letters->slice(0, 100)->toArray());
echo PHP_EOL;
```

```
array (
  0 => 'c',
  1 => 'd',
  2 => 'e',
  3 => 'f',
)
array (
  0 => 'g',
)
array (
  0 => 'a',
  1 => 'b',
  2 => 'c',
)
array (
  0 => 'a',
  1 => 'b',
  2 => 'c',
  3 => 'd',
  4 => 'e',
  5 => 'f',
  6 => 'g',
)
```

### ArrayValue::diff

```php
<?php
/**
 * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
 * @return ArrayValue
 */
public function diff(ArrayValue $other, ?callable $comparator = null);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$one = Wrap::array(['a', 'b', 'c', 'd', 'e', 'f', 'g']);
$two = Wrap::array(['c', 'd', 'e', 'F']);

var_export($one->diff($two)->toArray());

$lowercaseComparator = function(string $a, string $b): int {
    return mb_strtolower($a) <=> mb_strtolower($b);
};

var_export($one->diff($two, $lowercaseComparator)->toArray());
```

```
array (
  0 => 'a',
  1 => 'b',
  2 => 'f',
  3 => 'g',
)array (
  0 => 'a',
  1 => 'b',
  2 => 'g',
)
```

### ArrayValue::intersect

```php
<?php
/**
 * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
 * @return ArrayValue
 */
public function intersect(ArrayValue $other, ?callable $comparator = null);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$one = Wrap::array(['a', 'b', 'c', 'd', 'e', 'f', 'g']);
$two = Wrap::array(['c', 'd', 'e', 'F']);

var_export($one->intersect($two)->toArray());

$lowercaseComparator = function(string $a, string $b): int {
    return mb_strtolower($a) <=> mb_strtolower($b);
};

var_export($one->intersect($two, $lowercaseComparator)->toArray());
```

```
array (
  0 => 'c',
  1 => 'd',
  2 => 'e',
)array (
  0 => 'c',
  1 => 'd',
  2 => 'e',
  3 => 'f',
)
```

### ArrayValue::reduce

```php
<?php
/**
 * @param callable $transformer function(mixed $reduced, mixed $value): mixed
 * @param mixed $start
 * @return mixed
 */
public function reduce(callable $transformer, $start);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$prices = Wrap::array([10, 20, 50, 120]);

$summarize = function(int $sum, int $price): int {
    return $sum + $price;
};

echo 'Sum: ' . $prices->reduce($summarize, 0);
echo PHP_EOL;

$list = function(string $list, int $price): string {
    return $list . " €{$price},-";
};

echo 'Prices: ' . $prices->reduce($list, '');
echo PHP_EOL;
```

```
Sum: 200
Prices:  €10,- €20,- €50,- €120,-
```

### ArrayValue::implode

```php
<?php

public function implode(string $glue): StringValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$prices = Wrap::array(['a', 'b', 'c', 'd']);

echo $prices->implode(' / ')->toString();
```

```
a / b / c / d
```

### ArrayValue::notEmpty

```php
<?php
/**
 * @return ArrayValue
 */
public function notEmpty();
```

### ArrayValue::toAssocValue

```php
<?php

public function toAssocValue(): AssocValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c', 'd', 'e', 'f', 'g']);

var_export(
    $array->toAssocValue()
        ->mapKeys(function (string $oldKey, string $value): string {
            return "{$oldKey}:{$value}";
        })
        ->toAssocArray()
);
```

```
array (
  '0:a' => 'a',
  '1:b' => 'b',
  '2:c' => 'c',
  '3:d' => 'd',
  '4:e' => 'e',
  '5:f' => 'f',
  '6:g' => 'g',
)
```

### ArrayValue::toStringsArray

```php
<?php

public function toStringsArray(): StringsArray;
```

### Value::isEmpty

```php
<?php

public function isEmpty(): bool;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

echo "['a']: ";
var_export(Wrap::array(['a'])->isEmpty());
echo PHP_EOL;

echo "[]: ";
var_export(Wrap::array([])->isEmpty());
echo PHP_EOL;

echo "'a': ";
var_export(Wrap::string('a')->isEmpty());
echo PHP_EOL;

echo "'': ";
var_export(Wrap::string('')->isEmpty());
echo PHP_EOL;
```

```
['a']: false
[]: true
'a': false
'': true
```

### Collection::first

```php
<?php
/**
 * @return mixed
 */
public function first();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c']);

echo 'array first: ' . $array->first();
echo PHP_EOL;

$assoc = Wrap::assocArray(['a' => 1, 'b' => 2, 'c' => 3]);

echo 'assoc first: ' . $assoc->first();
echo PHP_EOL;
```

```
array first: a
assoc first: 1
```

### Collection::last

```php
<?php
/**
 * @return mixed
 */
public function last();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c']);

echo 'array last: ' . $array->last();
echo PHP_EOL;

$assoc = Wrap::assocArray(['a' => 1, 'b' => 2, 'c' => 3]);

echo 'assoc last: ' . $assoc->last();
echo PHP_EOL;
```

```
array last: c
assoc last: 3
```

### Collection::hasElement

```php
<?php
/**
 * @param mixed $element
 */
public function hasElement($element): bool;
```

### Countable::count

```php
<?php

public function count(): int;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c']);

echo 'count: ' . $array->count();
```

```
count: 3
```

### IteratorAggregate::getIterator

*(definition not available)*

## AssocValue

`AssocValue` defines interface of value object for associative array.

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::assocArray(['a' => 1, 'b' => 2, 'c' => 3]);

$array['a']; // 1
$array[0]; // Exception
$array['d']; // Exception
$has = isset($array['a']); // true
$has = isset($array['d']); // false
$array->count(); // 3

foreach ($array as $key => $value) {
    echo "$key => $value ";
} // outputs: a => 1 b => 2 c => 3

```


### AssocValue::each

```php
<?php
/**
 * @param callable $callback function(mixed $value): void
 * @return AssocValue
 */
public function each(callable $callback);
```

### AssocValue::unique

```php
<?php
/**
 * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
 * @return AssocValue
 */
public function unique(?callable $comparator = null);
```

### AssocValue::filter

```php
<?php
/**
 * @param callable $transformer function(mixed $value): bool { ... }
 * @return AssocValue
 */
public function filter(callable $transformer);
```

### AssocValue::filterEmpty

```php
<?php
/**
 * @return AssocValue
 */
public function filterEmpty();
```

### AssocValue::map

```php
<?php
/**
 * @param callable $transformer function(mixed $value[, string $key]): mixed { ... }
 * @return AssocValue
 */
public function map(callable $transformer);
```

### AssocValue::sort

```php
<?php
/**
 * @param callable $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
 * @return AssocValue
 */
public function sort(callable $comparator);
```

### AssocValue::shuffle

```php
<?php
/**
 * @return AssocValue
 */
public function shuffle();
```

### AssocValue::reverse

```php
<?php
/**
 * @return AssocValue
 */
public function reverse();
```

### AssocValue::offsetExists

```php
<?php
/**
 * @param string $offset
 */
public function offsetExists($offset): bool;
```

### AssocValue::offsetGet

```php
<?php
/**
 * @param string $offset
 * @return mixed
 */
public function offsetGet($offset);
```

### AssocValue::offsetSet

```php
<?php
/**
 * @param string $offset
 * @param mixed $value
 * @return void
 * @throws \BadMethodCallException For immutable types.
 */
public function offsetSet($offset, $value);
```

### AssocValue::offsetUnset

```php
<?php
/**
 * @param string $offset
 * @return void
 * @throws \BadMethodCallException For immutable types.
 */
public function offsetUnset($offset);
```

### AssocValue::toAssocArray

```php
<?php

public function toAssocArray(): array;
```

### AssocValue::keys

```php
<?php

public function keys(): StringsArray;
```

### AssocValue::values

```php
<?php

public function values(): ArrayValue;
```

### AssocValue::mapKeys

```php
<?php
/**
 * @param callable $transformer function(string $key[, mixed $value]): string
 * @return AssocValue
 */
public function mapKeys(callable $transformer);
```

### AssocValue::sortKeys

```php
<?php
/**
 * @param callable $comparator function(string $keyA, string $keyB): int{-1, 1}
 * @return AssocValue
 */
public function sortKeys(callable $comparator);
```

### AssocValue::with

```php
<?php
/**
 * @param mixed $value
 * @return AssocValue
 */
public function with(string $key, $value);
```

### AssocValue::without

```php
<?php
/**
 * @param string $key
 * @return AssocValue
 */
public function without(string $key);
```

### AssocValue::withoutElement

```php
<?php
/**
 * @param mixed $value
 * @return AssocValue
 */
public function withoutElement($value);
```

### AssocValue::merge

```php
<?php
/**
 * @param AssocValue $other
 * @return AssocValue
 */
public function merge(AssocValue $other);
```

### AssocValue::reduce

```php
<?php
/**
 * @param callable $transformer function(mixed $reduced, mixed $value, string $key): mixed
 * @param mixed $start
 * @return mixed
 */
public function reduce(callable $transformer, $start);
```

### AssocValue::get

```php
<?php
/**
 * @param mixed $default
 * @return mixed
 */
public function get(string $key, $default = null);
```

### AssocValue::has

```php
<?php

public function has(string $key): bool;
```

### Value::isEmpty

```php
<?php

public function isEmpty(): bool;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

echo "['a']: ";
var_export(Wrap::array(['a'])->isEmpty());
echo PHP_EOL;

echo "[]: ";
var_export(Wrap::array([])->isEmpty());
echo PHP_EOL;

echo "'a': ";
var_export(Wrap::string('a')->isEmpty());
echo PHP_EOL;

echo "'': ";
var_export(Wrap::string('')->isEmpty());
echo PHP_EOL;
```

```
['a']: false
[]: true
'a': false
'': true
```

### Collection::first

```php
<?php
/**
 * @return mixed
 */
public function first();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c']);

echo 'array first: ' . $array->first();
echo PHP_EOL;

$assoc = Wrap::assocArray(['a' => 1, 'b' => 2, 'c' => 3]);

echo 'assoc first: ' . $assoc->first();
echo PHP_EOL;
```

```
array first: a
assoc first: 1
```

### Collection::last

```php
<?php
/**
 * @return mixed
 */
public function last();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c']);

echo 'array last: ' . $array->last();
echo PHP_EOL;

$assoc = Wrap::assocArray(['a' => 1, 'b' => 2, 'c' => 3]);

echo 'assoc last: ' . $assoc->last();
echo PHP_EOL;
```

```
array last: c
assoc last: 3
```

### Collection::hasElement

```php
<?php
/**
 * @param mixed $element
 */
public function hasElement($element): bool;
```

### Collection::toArray

```php
<?php
/**
 * @return mixed[]
 */
public function toArray(): array;
```

### Countable::count

```php
<?php

public function count(): int;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c']);

echo 'count: ' . $array->count();
```

```
count: 3
```

### IteratorAggregate::getIterator

*(definition not available)*

## StringValue

### StringValue::stripTags

```php
<?php
/**
 * @return StringValue
 */
public function stripTags();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$html = Wrap::string('<p>Html is <strong>cool</strong> but not always...</p>');

echo $html->stripTags()->toString();
```

```
Html is cool but not always...
```

### StringValue::trim

```php
<?php
/**
 * @return StringValue
 */
public function trim(string $characterMask = self::TRIM_MASK);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::string(' :.: I ♡ SPACE :.:  ');

echo $text->trim()->toString() . PHP_EOL;
echo $text->trim(' .:')->toString() . PHP_EOL;
```

```
:.: I ♡ SPACE :.:
I ♡ SPACE
```

### StringValue::trimRight

```php
<?php
/**
 * @return StringValue
 */
public function trimRight(string $characterMask = self::TRIM_MASK);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::string(' :.: I ♡ SPACE :.:  ');

echo $text->trimRight()->toString() . PHP_EOL;
echo $text->trimRight(' .:')->toString() . PHP_EOL;
```

```
:.: I ♡ SPACE :.:
 :.: I ♡ SPACE
```

### StringValue::trimLeft

```php
<?php
/**
 * @return StringValue
 */
public function trimLeft(string $characterMask = self::TRIM_MASK);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::string(' :.: I ♡ SPACE :.:  ');

echo $text->trimLeft()->toString() . PHP_EOL;
echo $text->trimLeft(' .:')->toString() . PHP_EOL;
```

```
:.: I ♡ SPACE :.:  
I ♡ SPACE :.:
```

### StringValue::lower

```php
<?php
/**
 * @return StringValue
 */
public function lower();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::string('SOMETIMES I WANNA SCREAM!');

echo $text->lower()->toString() . PHP_EOL;
```

```
sometimes i wanna scream!
```

### StringValue::upper

```php
<?php
/**
 * @return StringValue
 */
public function upper();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::string('it`s so quiet...');

echo $text->upper()->toString() . PHP_EOL;
```

```
IT`S SO QUIET...
```

### StringValue::lowerFirst

```php
<?php
/**
 * @return StringValue
 */
public function lowerFirst();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::string('CamelCaseMethod()');

echo $text->lowerFirst()->toString() . PHP_EOL;
```

```
camelCaseMethod()
```

### StringValue::upperFirst

```php
<?php
/**
 * @return StringValue
 */
public function upperFirst();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::string('words don`t come easy');

echo $text->upperFirst()->toString() . PHP_EOL;
```

```
Words don`t come easy
```

### StringValue::upperWords

```php
<?php
/**
 * @return StringValue
 */
public function upperWords();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$html = Wrap::string('words don`t come easy');

echo $html->upperWords()->toString() . PHP_EOL;
```

```
Words Don`t Come Easy
```

### StringValue::padRight

```php
<?php
/**
 * @return StringValue
 */
public function padRight(int $length, string $string = ' ');
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::string('cut here ☞');

echo $text->padRight(16, '-')->toString();
```

```
cut here ☞----
```

### StringValue::padLeft

```php
<?php
/**
 * @return StringValue
 */
public function padLeft(int $length, string $string = ' ');
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::string('☜ cut here');

echo $text->padLeft(16, '-')->toString();
```

```
----☜ cut here
```

### StringValue::padBoth

```php
<?php
/**
 * @return StringValue
 */
public function padBoth(int $length, string $string = ' ');
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::string('☜ cut here ☞');

echo $text->padBoth(24, '-')->toString();
```

```
----☜ cut here ☞----
```

### StringValue::replace

```php
<?php
/**
 * @return StringValue
 */
public function replace(string $search, string $replace);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::string('My favourite color is pink!');

echo $text->replace('pink', 'blue')->toString();
```

```
My favourite color is blue!
```

### StringValue::replacePattern

```php
<?php
/**
 * @return StringValue
 */
public function replacePattern(string $pattern, string $replacement);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::string('You are looking good! Really!');

echo $text->replacePattern('/[aeiouy]/', '')->toString();
```

```
Y r lkng gd! Rll!
```

### StringValue::replacePatternCallback

```php
<?php
/**
 * @return StringValue
 */
public function replacePatternCallback(string $pattern, callable $callback);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::string('You are looking good! Really!');

$replacer = function(array $matches): string {
    $vowel = $matches[0];

    switch ($vowel) {
        case 'a':
            return 'o';

        case 'o':
            return 'a';

        default:
            return 'i';
    }
};

echo $text->replacePatternCallback('/[aeiouy]/', $replacer)->toString();
```

```
Yai ori laaking gaad! Riolli!
```

### StringValue::truncate

```php
<?php
/**
 * @return StringValue
 */
public function truncate(int $length, string $postfix = '...');
```

#### Examples

```php
<?php

use GW\Value\Wrap;

echo Wrap::string('It`s Short')->truncate(10)->toString() . PHP_EOL;
echo Wrap::string('This one is too long!')->truncate(10)->toString() . PHP_EOL;
echo Wrap::string('This one is too long!')->truncate(10, '+')->toString() . PHP_EOL;
```

```
It`s Short
This one i...
This one i+
```

### StringValue::substring

```php
<?php
/**
 * @return StringValue
 */
public function substring(int $start, ?int $length = null);
```

### StringValue::postfix

```php
<?php
/**
 * @return StringValue
 */
public function postfix(StringValue $other);
```

### StringValue::prefix

```php
<?php
/**
 * @return StringValue
 */
public function prefix(StringValue $other);
```

### StringValue::length

```php
<?php

public function length(): int;
```

### StringValue::position

```php
<?php

public function position(string $needle): ?int;
```

### StringValue::positionLast

```php
<?php

public function positionLast(string $needle): ?int;
```

### StringValue::matchAllPatterns

```php
<?php
/**
 * @return StringsArray
 */
public function matchAllPatterns(string $pattern);
```

### StringValue::matchPatterns

```php
<?php
/**
 * @return StringsArray
 */
public function matchPatterns(string $pattern);
```

### StringValue::isMatching

```php
<?php

public function isMatching(string $pattern): bool;
```

### StringValue::splitByPattern

```php
<?php
/**
 * @return StringsArray
 */
public function splitByPattern(string $pattern);
```

### StringValue::explode

```php
<?php
/**
 * @return StringsArray
 */
public function explode(string $delimiter);
```

### StringValue::contains

```php
<?php

public function contains(string $substring): bool;
```

### StringValue::toString

```php
<?php

public function toString(): string;
```

### StringValue::__toString

```php
<?php

public function __toString(): string;
```

### Value::isEmpty

```php
<?php

public function isEmpty(): bool;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

echo "['a']: ";
var_export(Wrap::array(['a'])->isEmpty());
echo PHP_EOL;

echo "[]: ";
var_export(Wrap::array([])->isEmpty());
echo PHP_EOL;

echo "'a': ";
var_export(Wrap::string('a')->isEmpty());
echo PHP_EOL;

echo "'': ";
var_export(Wrap::string('')->isEmpty());
echo PHP_EOL;
```

```
['a']: false
[]: true
'a': false
'': true
```


## StringsArray

### StringsArray::each

```php
<?php
/**
 * @param callable $callback function(StringValue $value): void
 * @return StringsArray
 */
public function each(callable $callback);
```

### StringsArray::unique

```php
<?php
/**
 * @param callable|null $comparator function(StringValue $valueA, StringValue $valueB): int{-1, 0, 1}
 * @return StringsArray
 */
public function unique(?callable $comparator = null);
```

### StringsArray::toArray

```php
<?php
/**
 * @return StringValue[]
 */
public function toArray(): array;
```

### StringsArray::filter

```php
<?php
/**
 * @param callable $transformer function(StringValue $value): bool
 * @return StringsArray
 */
public function filter(callable $transformer);
```

### StringsArray::filterEmpty

```php
<?php
/**
 * @return StringsArray
 */
public function filterEmpty();
```

### StringsArray::map

```php
<?php
/**
 * @param callable $transformer function(StringValue $value): StringValue|string
 * @return StringsArray
 */
public function map(callable $transformer);
```

### StringsArray::sort

```php
<?php
/**
 * @param callable $comparator function(StringValue $valueA, StringValue $valueB): int{-1, 0, 1}
 * @return StringsArray
 */
public function sort(callable $comparator);
```

### StringsArray::shuffle

```php
<?php
/**
 * @return StringsArray
 */
public function shuffle();
```

### StringsArray::reverse

```php
<?php
/**
 * @return StringsArray
 */
public function reverse();
```

### StringsArray::unshift

```php
<?php
/**
 * @param StringValue|string $value
 * @return StringsArray
 */
public function unshift($value);
```

### StringsArray::shift

```php
<?php
/**
 * @param mixed $value
 * @return StringsArray
 */
public function shift(&$value = null);
```

### StringsArray::push

```php
<?php
/**
 * @param StringValue|string $value
 * @return StringsArray
 */
public function push($value);
```

### StringsArray::pop

```php
<?php
/**
 * @param mixed $value
 * @return StringsArray
 */
public function pop(&$value = null);
```

### StringsArray::offsetExists

```php
<?php
/**
 * @param int $offset
 */
public function offsetExists($offset): bool;
```

### StringsArray::offsetGet

```php
<?php
/**
 * @param int $offset
 * @return StringValue
 */
public function offsetGet($offset);
```

### StringsArray::offsetSet

```php
<?php
/**
 * @param int $offset
 * @param StringValue|string $value
 * @return void
 * @throws \BadMethodCallException For immutable types.
 */
public function offsetSet($offset, $value);
```

### StringsArray::offsetUnset

```php
<?php
/**
 * @param int $offset
 * @return void
 * @throws \BadMethodCallException For immutable types.
 */
public function offsetUnset($offset);
```

### StringsArray::join

```php
<?php
/**
 * @return StringsArray
 */
public function join(ArrayValue $other);
```

### StringsArray::slice

```php
<?php
/**
 * @return StringsArray
 */
public function slice(int $offset, int $length);
```

### StringsArray::diff

```php
<?php
/**
 * @param callable|null $comparator function(StringValue $valueA, StringValue $valueB): int{-1, 0, 1}
 * @return StringsArray
 */
public function diff(ArrayValue $other, ?callable $comparator = null);
```

### StringsArray::intersect

```php
<?php
/**
 * @param callable|null $comparator function(StringValue $valueA, StringValue $valueB): int{-1, 0, 1}
 * @return StringsArray
 */
public function intersect(ArrayValue $other, ?callable $comparator = null);
```

### StringsArray::reduce

```php
<?php
/**
 * @param callable $transformer function(mixed $reduced, StringValue $value): mixed
 * @param mixed $start
 * @return mixed
 */
public function reduce(callable $transformer, $start);
```

### StringsArray::implode

```php
<?php

public function implode(string $glue): StringValue;
```

### StringsArray::notEmpty

```php
<?php
/**
 * @return StringsArray
 */
public function notEmpty();
```

### StringsArray::first

```php
<?php
/**
 * @return StringValue|null
 */
public function first();
```

### StringsArray::last

```php
<?php
/**
 * @return StringValue|null
 */
public function last();
```

### StringsArray::stripTags

```php
<?php
/**
 * @return StringsArray
 */
public function stripTags();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['<h1>Story</h1>', '<h2>Chapter 1</h2>', '<p>Once upon a time...</p>']);

var_export($text->stripTags()->toArray());
```

```
array (
  0 => 'Story',
  1 => 'Chapter 1',
  2 => 'Once upon a time...',
)
```

### StringsArray::trim

```php
<?php
/**
 * @return StringsArray
 */
public function trim(string $characterMask = self::TRIM_MASK);
```

### StringsArray::trimRight

```php
<?php
/**
 * @return StringsArray
 */
public function trimRight(string $characterMask = self::TRIM_MASK);
```

### StringsArray::trimLeft

```php
<?php
/**
 * @return StringsArray
 */
public function trimLeft(string $characterMask = self::TRIM_MASK);
```

### StringsArray::lower

```php
<?php
/**
 * @return StringsArray
 */
public function lower();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['SOMETIMES', 'I', 'WANNA', 'SCREAM!']);

var_export($text->lower()->toArray());
```

```
array (
  0 => 'sometimes',
  1 => 'i',
  2 => 'wanna',
  3 => 'scream!',
)
```

### StringsArray::upper

```php
<?php
/**
 * @return StringsArray
 */
public function upper();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['it`s so quiet', 'and peaceful']);

var_export($text->upper()->toArray());
```

```
array (
  0 => 'IT`S SO QUIET',
  1 => 'AND PEACEFUL',
)
```

### StringsArray::lowerFirst

```php
<?php
/**
 * @return StringsArray
 */
public function lowerFirst();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['CamelCaseMethod', 'AnotherCamel']);

var_export($text->lowerFirst()->toArray());
```

```
array (
  0 => 'camelCaseMethod',
  1 => 'anotherCamel',
)
```

### StringsArray::upperFirst

```php
<?php
/**
 * @return StringsArray
 */
public function upperFirst();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['it`s so quiet', 'and peaceful']);

var_export($text->upperFirst()->toString());
```

```
'It`s so quiet And peaceful'
```

### StringsArray::upperWords

```php
<?php
/**
 * @return StringsArray
 */
public function upperWords();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['it`s so quiet', 'and peaceful']);

var_export($text->upperWords()->toString());
```

```
'It`s So Quiet And Peaceful'
```

### StringsArray::padRight

```php
<?php
/**
 * @return StringsArray
 */
public function padRight(int $length, string $string = ' ');
```

### StringsArray::padLeft

```php
<?php
/**
 * @return StringsArray
 */
public function padLeft(int $length, string $string = ' ');
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['one', 'two', 'three']);

var_export($text->padLeft(16, '-')->toArray());
```

```
array (
  0 => '-------------one',
  1 => '-------------two',
  2 => '-----------three',
)
```

### StringsArray::padBoth

```php
<?php
/**
 * @return StringsArray
 */
public function padBoth(int $length, string $string = ' ');
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['one', 'two', 'three']);

var_export($text->padBoth(24, '-')->toArray());
```

```
array (
  0 => '----------one-----------',
  1 => '----------two-----------',
  2 => '---------three----------',
)
```

### StringsArray::replace

```php
<?php
/**
 * @return StringsArray
 */
public function replace(string $search, string $replace);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['One.', 'Two.', 'Three.']);

var_export($text->replace('.', '!!!')->toArray());
```

```
array (
  0 => 'One!!!',
  1 => 'Two!!!',
  2 => 'Three!!!',
)
```

### StringsArray::replacePattern

```php
<?php
/**
 * @return StringsArray
 */
public function replacePattern(string $pattern, string $replacement);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['Please', 'censor', 'all', 'vowels!']);

var_export($text->replacePattern('/[aeiouy]/', '*')->toArray());
```

```
array (
  0 => 'Pl**s*',
  1 => 'c*ns*r',
  2 => '*ll',
  3 => 'v*w*ls!',
)
```

### StringsArray::replacePatternCallback

```php
<?php
/**
 * @return StringsArray
 */
public function replacePatternCallback(string $pattern, callable $callback);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['Please', 'censor', 'all', 'vowels!']);

$replacer = function (array $match): string {
    $letter = $match[0];

    return '(' . $letter . ')';
};

var_export($text->replacePatternCallback('/[aeiouy]/', $replacer)->toArray());
```

```
array (
  0 => 'Pl(e)(a)s(e)',
  1 => 'c(e)ns(o)r',
  2 => '(a)ll',
  3 => 'v(o)w(e)ls!',
)
```

### StringsArray::truncate

```php
<?php
/**
 * @return StringsArray
 */
public function truncate(int $length, string $postfix = '...');
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['short', 'quite long', 'very very long']);

var_export($text->truncate(5, '~~')->toArray());
```

```
array (
  0 => 'short',
  1 => 'quite~~',
  2 => 'very ~~',
)
```

### StringsArray::substring

```php
<?php
/**
 * @return StringsArray
 */
public function substring(int $start, ?int $length = null);
```

### StringsArray::postfix

```php
<?php
/**
 * @return StringsArray
 */
public function postfix(StringValue $other);
```

### StringsArray::prefix

```php
<?php
/**
 * @return StringsArray
 */
public function prefix(StringValue $other);
```

### StringsArray::toArrayValue

```php
<?php
/**
 * @return ArrayValue<StringValue>
 */
public function toArrayValue(): ArrayValue;
```

#### Examples

```php
<?php

use GW\Value\StringValue;
use GW\Value\Wrap;

$men = ['Jack', 'John'];
$women = ['Mary', 'Tia'];

$array = Wrap::stringsArray(['John Black', 'Mary White', 'Jack Sparrow', 'Tia Dalma', 'Conchita Wurst']);

var_export(
    $array->toArrayValue()
        ->map(function (StringValue $fullName) use ($women, $men): array {
            [$name, $surname] = explode(' ', $fullName->toString());
            $sex = in_array($name, $men, true) ? 'male' : (in_array($name, $women, true) ? 'female' : 'other');

            return ['name' => $name, 'surname' => $surname, 'sex' => $sex];
        })
        ->toArray()
);
```

```
array (
  0 => 
  array (
    'name' => 'John',
    'surname' => 'Black',
    'sex' => 'male',
  ),
  1 => 
  array (
    'name' => 'Mary',
    'surname' => 'White',
    'sex' => 'female',
  ),
  2 => 
  array (
    'name' => 'Jack',
    'surname' => 'Sparrow',
    'sex' => 'male',
  ),
  3 => 
  array (
    'name' => 'Tia',
    'surname' => 'Dalma',
    'sex' => 'female',
  ),
  4 => 
  array (
    'name' => 'Conchita',
    'surname' => 'Wurst',
    'sex' => 'other',
  ),
)
```

### StringsArray::toAssocValue

```php
<?php
/**
 * @return AssocValue<string, StringValue>
 */
public function toAssocValue(): AssocValue;
```

#### Examples

```php
<?php

use GW\Value\StringValue;
use GW\Value\Wrap;

$array = Wrap::stringsArray(['John Black', 'Mary White', 'Jack Sparrow', 'Tia Dalma', 'Conchita Wurst']);

var_export(
    $array->toAssocValue()
        ->map(function (StringValue $person): string {
            return $person->toString();
        })
        ->mapKeys(function (string $oldKey, string $person): string {
            return $person;
        })
        ->toAssocArray()
);
```

```
array (
  'John Black' => 'John Black',
  'Mary White' => 'Mary White',
  'Jack Sparrow' => 'Jack Sparrow',
  'Tia Dalma' => 'Tia Dalma',
  'Conchita Wurst' => 'Conchita Wurst',
)
```

### ArrayValue::toStringsArray

```php
<?php

public function toStringsArray(): StringsArray;
```

### Value::isEmpty

```php
<?php

public function isEmpty(): bool;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

echo "['a']: ";
var_export(Wrap::array(['a'])->isEmpty());
echo PHP_EOL;

echo "[]: ";
var_export(Wrap::array([])->isEmpty());
echo PHP_EOL;

echo "'a': ";
var_export(Wrap::string('a')->isEmpty());
echo PHP_EOL;

echo "'': ";
var_export(Wrap::string('')->isEmpty());
echo PHP_EOL;
```

```
['a']: false
[]: true
'a': false
'': true
```

### Collection::hasElement

```php
<?php
/**
 * @param mixed $element
 */
public function hasElement($element): bool;
```

### Countable::count

```php
<?php

public function count(): int;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c']);

echo 'count: ' . $array->count();
```

```
count: 3
```

### IteratorAggregate::getIterator

*(definition not available)*
### StringValue::length

```php
<?php

public function length(): int;
```

### StringValue::position

```php
<?php

public function position(string $needle): ?int;
```

### StringValue::positionLast

```php
<?php

public function positionLast(string $needle): ?int;
```

### StringValue::matchAllPatterns

```php
<?php
/**
 * @return StringsArray
 */
public function matchAllPatterns(string $pattern);
```

### StringValue::matchPatterns

```php
<?php
/**
 * @return StringsArray
 */
public function matchPatterns(string $pattern);
```

### StringValue::isMatching

```php
<?php

public function isMatching(string $pattern): bool;
```

### StringValue::splitByPattern

```php
<?php
/**
 * @return StringsArray
 */
public function splitByPattern(string $pattern);
```

### StringValue::explode

```php
<?php
/**
 * @return StringsArray
 */
public function explode(string $delimiter);
```

### StringValue::contains

```php
<?php

public function contains(string $substring): bool;
```

### StringValue::toString

```php
<?php

public function toString(): string;
```

### StringValue::__toString

```php
<?php

public function __toString(): string;
```



