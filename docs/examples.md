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
 * @param callable(TValue $value):void $callback
 * @phpstan-return ArrayValue<TValue>
 */
public function each(callable $callback): ArrayValue;
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
 * @param (callable(TValue,TValue):int)|null $comparator
 * @phpstan-return ArrayValue<TValue>
 */
public function unique(?callable $comparator = null): ArrayValue;
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
 * @phpstan-return array<int, TValue>
 */
public function toArray(): array;
```

Return primitive `array` from subject `ArrayValue`.

### ArrayValue::filter

```php
<?php
/**
 * @param callable(TValue $value):bool $filter
 * @phpstan-return ArrayValue<TValue>
 */
public function filter(callable $filter): ArrayValue;
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
 * @phpstan-return ArrayValue<TValue>
 */
public function filterEmpty(): ArrayValue;
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
 * @template TNewValue
 * @param callable(TValue $value):TNewValue $transformer
 * @phpstan-return ArrayValue<TNewValue>
 */
public function map(callable $transformer): ArrayValue;
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

### ArrayValue::flatMap

```php
<?php
/**
 * @template TNewValue
 * @param callable(TValue $value):iterable<TNewValue> $transformer
 * @phpstan-return ArrayValue<TNewValue>
 */
public function flatMap(callable $transformer): ArrayValue;
```

### ArrayValue::groupBy

```php
<?php
/**
 * @template TNewKey of int|string
 * @param callable(TValue $value):TNewKey $reducer
 * @phpstan-return AssocValue<TNewKey, ArrayValue<TValue>>
 */
public function groupBy(callable $reducer): AssocValue;
```

Group items by key extracted from value by `$reducer` callback.

Result is `AssocValue` containing association: `['key1' => [items reduced to key1], 'key2' => [items reduced to key2]]`.

#### Examples

```php
<?php

use GW\Value\ArrayValue;
use GW\Value\Wrap;

$payments = Wrap::array([
    ['group' => 'food', 'amount' => 10],
    ['group' => 'drinks', 'amount' => 10],
    ['group' => 'food', 'amount' => 20],
    ['group' => 'travel', 'amount' => 500],
    ['group' => 'drinks', 'amount' => 20],
    ['group' => 'food', 'amount' => 50],
]);

$get = function (string $key): \Closure {
    return function (array $payment) use ($key): string {
        return $payment[$key];
    };
};

echo 'grouped expenses:', PHP_EOL;
var_export(
    $payments
        ->groupBy($get('group'))
        ->map(function (ArrayValue $group) use ($get): array {
            return $group->map($get('amount'))->toArray();
        })
        ->toAssocArray()
);
echo PHP_EOL, PHP_EOL;

$numbers = Wrap::array([1, 2, 3, 4, 3, 4, 5, 6, 7, 8, 9]);
$even = function (int $number): int {
    return $number % 2;
};

echo 'even partition:', PHP_EOL;
var_export(
    $numbers
        ->groupBy($even)
        ->map(function (ArrayValue $group): array {
            return $group->toArray();
        })
        ->toArray()
);
```

```
grouped expenses:
array (
  'food' => 
  array (
    0 => '10',
    1 => '20',
    2 => '50',
  ),
  'drinks' => 
  array (
    0 => '10',
    1 => '20',
  ),
  'travel' => 
  array (
    0 => '500',
  ),
)

even partition:
array (
  0 => 
  array (
    0 => 1,
    1 => 3,
    2 => 3,
    3 => 5,
    4 => 7,
    5 => 9,
  ),
  1 => 
  array (
    0 => 2,
    1 => 4,
    2 => 4,
    3 => 6,
    4 => 8,
  ),
)
```

### ArrayValue::chunk

```php
<?php
/**
 * @phpstan-return ArrayValue<array<int, TValue>>
 */
public function chunk(int $size): ArrayValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::array([1, 2, 3, 4, 3, 4, 5, 6, 7, 8, 9]);

var_export($array->chunk(3)->toArray());
```

```
array (
  0 => 
  array (
    0 => 1,
    1 => 2,
    2 => 3,
  ),
  1 => 
  array (
    0 => 4,
    1 => 3,
    2 => 4,
  ),
  2 => 
  array (
    0 => 5,
    1 => 6,
    2 => 7,
  ),
  3 => 
  array (
    0 => 8,
    1 => 9,
  ),
)
```

### ArrayValue::sort

```php
<?php
/**
 * @param callable(TValue,TValue):int $comparator
 * @phpstan-return ArrayValue<TValue>
 */
public function sort(callable $comparator): ArrayValue;
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
 * @phpstan-return ArrayValue<TValue>
 */
public function shuffle(): ArrayValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$words = Wrap::array(['do', 'or', 'do', 'not', 'there', 'is', 'no', 'try']);

echo $words->shuffle()->implode(' ')->toString();
```

```
no or there do try do not is
```

### ArrayValue::reverse

```php
<?php
/**
 * @phpstan-return ArrayValue<TValue>
 */
public function reverse(): ArrayValue;
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
 * @phpstan-param TValue $value
 * @phpstan-return ArrayValue<TValue>
 */
public function unshift($value): ArrayValue;
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
 * @phpstan-param TValue $value
 * @phpstan-return ArrayValue<TValue>
 */
public function shift(&$value = null): ArrayValue;
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
 * @phpstan-param TValue $value
 * @phpstan-return ArrayValue<TValue>
 */
public function push($value): ArrayValue;
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
 * @phpstan-param TValue|null $value
 * @phpstan-return ArrayValue<TValue>
 */
public function pop(&$value = null): ArrayValue;
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
 * @phpstan-return TValue
 */
public function offsetGet($offset);
```

### ArrayValue::offsetSet

```php
<?php
/**
 * @param int $offset
 * @phpstan-param TValue $value
 * @throws BadMethodCallException For immutable types.
 */
public function offsetSet($offset, $value): void;
```

### ArrayValue::offsetUnset

```php
<?php
/**
 * @param int $offset
 * @return void
 * @throws BadMethodCallException For immutable types.
 */
public function offsetUnset($offset): void;
```

### ArrayValue::join

```php
<?php
/**
 * @phpstan-param ArrayValue<TValue> $other
 * @phpstan-return ArrayValue<TValue>
 */
public function join(ArrayValue $other): ArrayValue;
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
 * @phpstan-return ArrayValue<TValue>
 */
public function slice(int $offset, ?int $length = null): ArrayValue;
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

### ArrayValue::skip

```php
<?php
/**
 * @phpstan-return ArrayValue<TValue>
 */
public function skip(int $length): ArrayValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$letters = Wrap::array(['a', 'b', 'c', 'd', 'e', 'f', 'g']);

var_export($letters->skip(2)->toArray());
echo PHP_EOL;
```

```
array (
  0 => 'c',
  1 => 'd',
  2 => 'e',
  3 => 'f',
  4 => 'g',
)
```

### ArrayValue::take

```php
<?php
/**
 * @phpstan-return ArrayValue<TValue>
 */
public function take(int $length): ArrayValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$letters = Wrap::array(['a', 'b', 'c', 'd', 'e', 'f', 'g']);

var_export($letters->skip(2)->take(4)->toArray());
echo PHP_EOL;

var_export($letters->take(3)->toArray());
echo PHP_EOL;

var_export($letters->take(100)->toArray());
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

### ArrayValue::splice

```php
<?php
/**
 * @phpstan-param ArrayValue<TValue> $replacement
 * @phpstan-return ArrayValue<TValue>
 */
public function splice(int $offset, int $length, ?ArrayValue $replacement = null): ArrayValue;
```

Remove or replace slice of `ArrayValue` items.

#### Examples

```php
<?php

use GW\Value\Wrap;

$letters = Wrap::array(['a', 'b', 'c', 'd', 'e', 'f', 'g']);

var_export($letters->splice(2, 4)->toArray());
echo PHP_EOL;

var_export($letters->splice(2, 4, Wrap::array(['x', 'y', 'z']))->toArray());
echo PHP_EOL;

var_export($letters->splice(-1, 1)->toArray());
echo PHP_EOL;

var_export($letters->splice(-1, 1, Wrap::array(['x', 'y']))->toArray());
echo PHP_EOL;

var_export($letters->splice(0, 3)->toArray());
echo PHP_EOL;

var_export($letters->splice(0, 100)->toArray());
echo PHP_EOL;
```

```
array (
  0 => 'a',
  1 => 'b',
  2 => 'g',
)
array (
  0 => 'a',
  1 => 'b',
  2 => 'x',
  3 => 'y',
  4 => 'z',
  5 => 'g',
)
array (
  0 => 'a',
  1 => 'b',
  2 => 'c',
  3 => 'd',
  4 => 'e',
  5 => 'f',
)
array (
  0 => 'a',
  1 => 'b',
  2 => 'c',
  3 => 'd',
  4 => 'e',
  5 => 'f',
  6 => 'x',
  7 => 'y',
)
array (
  0 => 'd',
  1 => 'e',
  2 => 'f',
  3 => 'g',
)
array (
)
```

### ArrayValue::diff

```php
<?php
/**
 * @phpstan-param ArrayValue<TValue> $other
 * @param (callable(TValue,TValue):int)|null $comparator
 * @phpstan-return ArrayValue<TValue>
 */
public function diff(ArrayValue $other, ?callable $comparator = null): ArrayValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$one = Wrap::array(['a', 'b', 'c', 'd', 'e', 'f', 'g']);
$two = Wrap::array(['c', 'd', 'e', 'F']);

var_export($one->diff($two)->toArray());
echo PHP_EOL;

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
)
array (
  0 => 'a',
  1 => 'b',
  2 => 'g',
)
```

### ArrayValue::intersect

```php
<?php
/**
 * @phpstan-param ArrayValue<TValue> $other
 * @param (callable(TValue,TValue):int)|null $comparator
 * @phpstan-return ArrayValue<TValue>
 */
public function intersect(ArrayValue $other, ?callable $comparator = null): ArrayValue;
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
 * @template TNewValue
 * @param callable(TNewValue, TValue):TNewValue $transformer
 * @phpstan-param TNewValue $start
 * @phpstan-return TNewValue
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
 * @phpstan-return ArrayValue<TValue>
 */
public function notEmpty(): ArrayValue;
```

### ArrayValue::toAssocValue

```php
<?php
/**
 * @phpstan-return AssocValue<int, TValue>
 */
public function toAssocValue(): AssocValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c', 'd', 'e', 'f', 'g']);

var_export(
    $array->toAssocValue()
        ->mapKeys(function (int $oldKey, string $value): string {
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

### ArrayValue::isEmpty

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
```

```
['a']: false
[]: true
```

### ArrayValue::first

```php
<?php
/**
 * @phpstan-return TValue|null
 */
public function first();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c']);

echo 'array first: ' . $array->first();
```

```
array first: a
```

### ArrayValue::last

```php
<?php
/**
 * @phpstan-return TValue|null
 */
public function last();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::array(['a', 'b', 'c']);

echo 'array last: ' . $array->last();
```

```
array last: c
```

### ArrayValue::find

```php
<?php
/**
 * @param callable(TValue $value):bool $filter
 * @phpstan-return TValue|null
 */
public function find(callable $filter);
```

### ArrayValue::findLast

```php
<?php
/**
 * @param callable(TValue $value): bool $filter
 * @phpstan-return TValue|null
 */
public function findLast(callable $filter);
```

### ArrayValue::hasElement

```php
<?php
/**
 * @phpstan-param TValue $element
 */
public function hasElement($element): bool;
```

### ArrayValue::any

```php
<?php
/**
 * @param callable(TValue $value):bool $filter
 */
public function any(callable $filter): bool;
```

### ArrayValue::every

```php
<?php
/**
 * @param callable(TValue $value):bool $filter
 */
public function every(callable $filter): bool;
```

### ArrayValue::count

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

### ArrayValue::getIterator

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
 * @phpstan-param callable(TValue, TKey $key):void $callback
 * @phpstan-return AssocValue<TKey, TValue>
 */
public function each(callable $callback): AssocValue;
```

### AssocValue::unique

```php
<?php
/**
 * @phpstan-param (callable(TValue,TValue):int)|null $comparator
 * @phpstan-return AssocValue<TKey, TValue>
 */
public function unique(?callable $comparator = null): AssocValue;
```

### AssocValue::filter

```php
<?php
/**
 * @phpstan-param callable(TValue):bool $filter
 * @phpstan-return AssocValue<TKey, TValue>
 */
public function filter(callable $filter): AssocValue;
```

### AssocValue::filterEmpty

```php
<?php
/**
 * @phpstan-return AssocValue<TKey, TValue>
 */
public function filterEmpty(): AssocValue;
```

### AssocValue::map

```php
<?php
/**
 * @template TNewValue
 * @param callable(TValue,TKey $key):TNewValue $transformer
 * @phpstan-return AssocValue<TKey, TNewValue>
 */
public function map(callable $transformer): AssocValue;
```

### AssocValue::sort

```php
<?php
/**
 * @param callable(TValue,TValue):int $comparator
 * @phpstan-return AssocValue<TKey, TValue>
 */
public function sort(callable $comparator): AssocValue;
```

### AssocValue::shuffle

```php
<?php
/**
 * @phpstan-return AssocValue<TKey, TValue>
 */
public function shuffle(): AssocValue;
```

### AssocValue::reverse

```php
<?php
/**
 * @phpstan-return AssocValue<TKey, TValue>
 */
public function reverse(): AssocValue;
```

### AssocValue::offsetExists

```php
<?php
/**
 * @phpstan-param TKey $offset
 */
public function offsetExists($offset): bool;
```

### AssocValue::offsetGet

```php
<?php
/**
 * @phpstan-param TKey $offset
 * @return ?TValue
 */
public function offsetGet($offset);
```

### AssocValue::offsetSet

```php
<?php
/**
 * @phpstan-param TKey $offset
 * @phpstan-param TValue $value
 * @throws BadMethodCallException For immutable types.
 */
public function offsetSet($offset, $value): void;
```

### AssocValue::offsetUnset

```php
<?php
/**
 * @phpstan-param TKey $offset
 * @throws BadMethodCallException For immutable types.
 */
public function offsetUnset($offset): void;
```

### AssocValue::toAssocArray

```php
<?php
/**
 * @phpstan-return array<TKey, TValue>
 */
public function toAssocArray(): array;
```

### AssocValue::keys

```php
<?php
/**
 * @phpstan-return ArrayValue<TKey>
 */
public function keys(): ArrayValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$assoc = Wrap::assocArray(['0' => 'zero', '1' => 'one']);

$keys = $assoc
    ->map(fn(string $val, int $key): string => $val)
    ->keys()
    ->toArray();

var_export($keys);
```

```
array (
  0 => 0,
  1 => 1,
)
```

### AssocValue::values

```php
<?php
/**
 * @phpstan-return ArrayValue<TValue>
 */
public function values(): ArrayValue;
```

### AssocValue::mapKeys

```php
<?php
/**
 * @template TNewKey of int|string
 * @phpstan-param callable(TKey $key, TValue $value): TNewKey $transformer
 * @phpstan-return AssocValue<TNewKey, TValue>
 */
public function mapKeys(callable $transformer): AssocValue;
```

### AssocValue::sortKeys

```php
<?php
/**
 * @phpstan-param callable(TKey $keyA, TKey $keyB): int $comparator
 * @phpstan-return AssocValue<TKey, TValue>
 */
public function sortKeys(callable $comparator): AssocValue;
```

### AssocValue::with

```php
<?php
/**
 * @phpstan-param TKey $key
 * @phpstan-param TValue $value
 * @phpstan-return AssocValue<TKey, TValue>
 */
public function with($key, $value): AssocValue;
```

### AssocValue::without

```php
<?php
/**
 * @phpstan-param TKey ...$keys
 * @phpstan-return AssocValue<TKey, TValue>
 */
public function without(...$keys): AssocValue;
```

### AssocValue::only

```php
<?php
/**
 * @param TKey ...$keys
 * @phpstan-return AssocValue<TKey, TValue>
 */
public function only(...$keys): AssocValue;
```

### AssocValue::withoutElement

```php
<?php
/**
 * @phpstan-param TValue $value
 * @phpstan-return AssocValue<TKey, TValue>
 */
public function withoutElement($value): AssocValue;
```

### AssocValue::merge

```php
<?php
/**
 * @deprecated use join() or replace() instead
 * @phpstan-param AssocValue<TKey, TValue> $other
 * @phpstan-return AssocValue<TKey, TValue>
 */
public function merge(AssocValue $other): AssocValue;
```

### AssocValue::join

```php
<?php
/**
 * Joins other AssocValue by adding the keys from other not present in self
 *
 * @phpstan-param AssocValue<TKey, TValue> $other
 * @phpstan-return AssocValue<TKey, TValue>
 */
public function join(AssocValue $other): AssocValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$one = Wrap::assocArray(['a' => 1, 'b' => 2, 'c' => 3]);
$two = Wrap::assocArray(['c' => 5, 'd' => 4]);

var_export($one->join($two)->toAssocArray());
```

```
array (
  'a' => 1,
  'b' => 2,
  'c' => 3,
  'd' => 4,
)
```

### AssocValue::replace

```php
<?php
/**
 * Joins other AssocValue by replacing values in self of the same keys from other
 *
 * @phpstan-param AssocValue<TKey, TValue> $other
 * @phpstan-return AssocValue<TKey, TValue>
 */
public function replace(AssocValue $other): AssocValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$one = Wrap::assocArray(['a' => 1, 'b' => 2, 'c' => 3]);
$two = Wrap::assocArray(['c' => 5, 'd' => 4]);

var_export($one->replace($two)->toAssocArray());
```

```
array (
  'a' => 1,
  'b' => 2,
  'c' => 5,
  'd' => 4,
)
```

### AssocValue::reduce

```php
<?php
/**
 * @template TNewValue
 * @param callable(TNewValue $reduced, TValue $value, string $key):TNewValue $transformer
 * @phpstan-param TNewValue $start
 * @phpstan-return TNewValue
 */
public function reduce(callable $transformer, $start);
```

### AssocValue::get

```php
<?php
/**
 * @phpstan-param TKey $key
 * @phpstan-param ?TValue $default
 * @phpstan-return ?TValue
 */
public function get($key, $default = null);
```

### AssocValue::has

```php
<?php
/**
 * @phpstan-param TKey $key
 */
public function has($key): bool;
```

### AssocValue::isEmpty

```php
<?php

public function isEmpty(): bool;
```

### AssocValue::first

```php
<?php
/**
 * @phpstan-return TValue|null
 */
public function first();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$assoc = Wrap::assocArray(['a' => 1, 'b' => 2, 'c' => 3]);

echo 'assoc first: ' . $assoc->first();
```

```
assoc first: 1
```

### AssocValue::last

```php
<?php
/**
 * @phpstan-return TValue|null
 */
public function last();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$assoc = Wrap::assocArray(['a' => 1, 'b' => 2, 'c' => 3]);

echo 'assoc last: ' . $assoc->last();
```

```
assoc last: 3
```

### AssocValue::find

```php
<?php
/**
 * @param callable(TValue $value):bool $filter
 * @phpstan-return TValue|null
 */
public function find(callable $filter);
```

### AssocValue::findLast

```php
<?php
/**
 * @param callable(TValue $value): bool $filter
 * @phpstan-return TValue|null
 */
public function findLast(callable $filter);
```

### AssocValue::hasElement

```php
<?php
/**
 * @phpstan-param TValue $element
 */
public function hasElement($element): bool;
```

### AssocValue::any

```php
<?php
/**
 * @param callable(TValue $value):bool $filter
 */
public function any(callable $filter): bool;
```

### AssocValue::every

```php
<?php
/**
 * @param callable(TValue $value):bool $filter
 */
public function every(callable $filter): bool;
```

### AssocValue::toArray

```php
<?php
/**
 * @phpstan-return array<int, TValue>
 */
public function toArray(): array;
```

### AssocValue::count

```php
<?php

public function count(): int;
```

### AssocValue::getIterator

*(definition not available)*

## StringValue

### StringValue::transform

```php
<?php
/**
 * @param callable(string $value):(StringValue|string) $transformer
 * @return StringValue
 */
public function transform(callable $transformer): StringValue;
```

### StringValue::stripTags

```php
<?php
/**
 * @return StringValue
 */
public function stripTags(): StringValue;
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
 * @param string|StringValue $characterMask
 */
public function trim($characterMask = self::TRIM_MASK): StringValue;
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
 * @param string|StringValue $characterMask
 */
public function trimRight($characterMask = self::TRIM_MASK): StringValue;
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
 * @param string|StringValue $characterMask
 */
public function trimLeft($characterMask = self::TRIM_MASK): StringValue;
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
public function lower(): StringValue;
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
public function upper(): StringValue;
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
public function lowerFirst(): StringValue;
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
public function upperFirst(): StringValue;
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
public function upperWords(): StringValue;
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
 * @param string|StringValue $string
 */
public function padRight(int $length, $string = ' '): StringValue;
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
 * @param string|StringValue $string
 */
public function padLeft(int $length, $string = ' '): StringValue;
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
 * @param string|StringValue $string
 */
public function padBoth(int $length, $string = ' '): StringValue;
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
 * @param string|StringValue $search
 * @param string|StringValue $replace
 */
public function replace($search, $replace): StringValue;
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

### StringValue::replaceAll

```php
<?php
/**
 * @return StringValue
 * @param array<int,string>|ArrayValue<string> $search
 * @param string|StringValue $replace
 */
public function replaceAll($search, $replace): StringValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::string('Your favourite colors are red and black');

echo $text->replaceAll(['red', 'black'], 'blue')->toString();
```

```
Your favourite colors are blue and blue
```

### StringValue::replacePairs

```php
<?php
/**
 * @return StringValue
 * @param array<string,string>|AssocValue<string,string> $pairs
 */
public function replacePairs($pairs): StringValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::string('Your favourite colors are red and black');

echo $text->replacePairs(['red' => 'orange', 'black' => 'white'])->toString();
```

```
Your favourite colors are orange and white
```

### StringValue::replacePattern

```php
<?php
/**
 * @return StringValue
 * @param string|StringValue $pattern
 * @param string|StringValue $replacement
 */
public function replacePattern($pattern, $replacement): StringValue;
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
 * @param string|StringValue $pattern
 */
public function replacePatternCallback($pattern, callable $callback): StringValue;
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
 * @param string|StringValue $postfix
 */
public function truncate(int $length, $postfix = '...'): StringValue;
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
public function substring(int $start, ?int $length = null): StringValue;
```

### StringValue::postfix

```php
<?php
/**
 * @return StringValue
 * @param string|StringValue $other
 */
public function postfix($other): StringValue;
```

### StringValue::prefix

```php
<?php
/**
 * @return StringValue
 * @param string|StringValue $other
 */
public function prefix($other): StringValue;
```

### StringValue::length

```php
<?php

public function length(): int;
```

### StringValue::position

```php
<?php
/**
 * @param string|StringValue $needle
 */
public function position($needle): ?int;
```

### StringValue::positionLast

```php
<?php
/**
 * @param string|StringValue $needle
 */
public function positionLast($needle): ?int;
```

### StringValue::matchAllPatterns

```php
<?php
/**
 * @param string|StringValue $pattern
 * @return ArrayValue<array<int, string>>
 */
public function matchAllPatterns($pattern): ArrayValue;
```

### StringValue::matchPatterns

```php
<?php
/**
 * @param string|StringValue $pattern
 * @return StringsArray
 */
public function matchPatterns($pattern): StringsArray;
```

### StringValue::isMatching

```php
<?php
/**
 * @param string|StringValue $pattern
 */
public function isMatching($pattern): bool;
```

### StringValue::startsWith

```php
<?php
/**
 * @param string|StringValue $pattern
 */
public function startsWith($pattern): bool;
```

### StringValue::endsWith

```php
<?php
/**
 * @param string|StringValue $pattern
 */
public function endsWith($pattern): bool;
```

### StringValue::splitByPattern

```php
<?php
/**
 * @return StringsArray
 * @param string|StringValue $pattern
 */
public function splitByPattern($pattern): StringsArray;
```

### StringValue::explode

```php
<?php
/**
 * @return StringsArray
 * @param string|StringValue $delimiter
 */
public function explode($delimiter): StringsArray;
```

### StringValue::contains

```php
<?php
/**
 * @param string|StringValue $substring
 */
public function contains($substring): bool;
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

### StringValue::isEmpty

```php
<?php

public function isEmpty(): bool;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

echo "'a': ";
var_export(Wrap::string('a')->isEmpty());
echo PHP_EOL;

echo "'': ";
var_export(Wrap::string('')->isEmpty());
echo PHP_EOL;
```

```
'a': false
'': true
```


## StringsArray

### StringsArray::each

```php
<?php
/**
 * @param callable(StringValue $value): void $callback
 */
public function each(callable $callback): StringsArray;
```

### StringsArray::any

```php
<?php
/**
 * @param callable(StringValue):bool $filter
 */
public function any(callable $filter): bool;
```

### StringsArray::every

```php
<?php
/**
 * @param callable(StringValue):bool $filter
 */
public function every(callable $filter): bool;
```

### StringsArray::unique

```php
<?php
/**
 * @param (callable(StringValue, StringValue):int)|null $comparator
 */
public function unique(?callable $comparator = null): StringsArray;
```

### StringsArray::toArray

```php
<?php
/**
 * @return array<int, StringValue>
 */
public function toArray(): array;
```

### StringsArray::toNativeStrings

```php
<?php
/**
 * @return string[]
 */
public function toNativeStrings(): array;
```

### StringsArray::filter

```php
<?php
/**
 * @param callable(StringValue):bool $filter
 */
public function filter(callable $filter): StringsArray;
```

### StringsArray::filterEmpty

```php
<?php

public function filterEmpty(): StringsArray;
```

### StringsArray::map

```php
<?php
/**
 * @param callable(StringValue):StringValue $transformer
 */
public function map(callable $transformer): StringsArray;
```

### StringsArray::flatMap

```php
<?php
/**
 * @param callable(StringValue):iterable<StringValue> $transformer
 */
public function flatMap(callable $transformer): StringsArray;
```

### StringsArray::groupBy

```php
<?php
/**
 * @template TNewKey of int|string
 * @param callable(StringValue):TNewKey $reducer
 * @phpstan-return AssocValue<TNewKey, StringsArray>
 */
public function groupBy(callable $reducer): AssocValue;
```

#### Examples

```php
<?php

use GW\Value\StringsArray;
use GW\Value\StringValue;
use GW\Value\Wrap;

$text = 'I would like to ask a question about the meaning of life';
$stopwords = ['i', 'to', 'a', 'the', 'of'];

$wordGroups = Wrap::string($text)
    ->lower()
    ->explode(' ')
    ->groupBy(function (StringValue $word) use ($stopwords): string {
        return in_array($word->toString(), $stopwords, true) ? 'stopwords' : 'words';
    });

/** @var StringsArray $stopwords */
$stopwords = $wordGroups->get('stopwords');
echo 'stopwords: ', $stopwords->implode(', ')->toString(), PHP_EOL;

/** @var StringsArray $words */
$words = $wordGroups->get('words');
echo 'words: ', $words->implode(', ')->toString(), PHP_EOL;
```

```
stopwords: i, to, a, the, of
words: would, like, ask, question, about, meaning, life
```

### StringsArray::sort

```php
<?php
/**
 * @param callable(StringValue,StringValue):int $comparator
 */
public function sort(callable $comparator): StringsArray;
```

### StringsArray::shuffle

```php
<?php

public function shuffle(): StringsArray;
```

### StringsArray::reverse

```php
<?php

public function reverse(): StringsArray;
```

### StringsArray::unshift

```php
<?php
/**
 * @param StringValue|string $value
 */
public function unshift($value): StringsArray;
```

### StringsArray::shift

```php
<?php
/**
 * @param StringValue|null $value
 */
public function shift(&$value = null): StringsArray;
```

### StringsArray::push

```php
<?php
/**
 * @param StringValue|string $value
 */
public function push($value): StringsArray;
```

### StringsArray::pop

```php
<?php
/**
 * @param StringValue|null $value
 */
public function pop(&$value = null): StringsArray;
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
 */
public function offsetGet($offset): StringValue;
```

### StringsArray::offsetSet

```php
<?php
/**
 * @param int $offset
 * @param StringValue|string $value
 * @throws BadMethodCallException For immutable types.
 */
public function offsetSet($offset, $value): void;
```

### StringsArray::offsetUnset

```php
<?php
/**
 * @param int $offset
 * @throws BadMethodCallException For immutable types.
 */
public function offsetUnset($offset): void;
```

### StringsArray::join

```php
<?php

public function join(StringsArray $other): StringsArray;
```

### StringsArray::slice

```php
<?php

public function slice(int $offset, ?int $length = null): StringsArray;
```

### StringsArray::skip

```php
<?php

public function skip(int $length): StringsArray;
```

### StringsArray::take

```php
<?php

public function take(int $length): StringsArray;
```

### StringsArray::splice

```php
<?php

public function splice(int $offset, int $length, ?StringsArray $replacement = null): StringsArray;
```

### StringsArray::diff

```php
<?php
/**
 * @param (callable(StringValue, StringValue):int)|null $comparator
 */
public function diff(StringsArray $other, ?callable $comparator = null): StringsArray;
```

### StringsArray::intersect

```php
<?php
/**
 * @param (callable(StringValue, StringValue):int)|null $comparator
 */
public function intersect(StringsArray $other, ?callable $comparator = null): StringsArray;
```

### StringsArray::reduce

```php
<?php
/**
 * @template TNewValue
 * @phpstan-param callable(TNewValue, StringValue):TNewValue $transformer
 * @phpstan-param TNewValue $start
 * @phpstan-return TNewValue
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
public function notEmpty(): StringsArray;
```

### StringsArray::first

```php
<?php
/**
 * @return StringValue|null
 */
public function first(): ?StringValue;
```

### StringsArray::last

```php
<?php
/**
 * @return StringValue|null
 */
public function last(): ?StringValue;
```

### StringsArray::find

```php
<?php
/**
 * @param callable(StringValue):bool $filter
 */
public function find(callable $filter): ?StringValue;
```

### StringsArray::findLast

```php
<?php
/**
 * @param callable(StringValue):bool $filter
 */
public function findLast(callable $filter): ?StringValue;
```

### StringsArray::transform

```php
<?php
/**
 * @param callable(string):(StringValue|string) $transformer
 */
public function transform(callable $transformer): StringsArray;
```

### StringsArray::stripTags

```php
<?php

public function stripTags(): StringsArray;
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
  0 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'Story',
  )),
  1 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'Chapter 1',
  )),
  2 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'Once upon a time...',
  )),
)
```

### StringsArray::trim

```php
<?php
/**
 * @param string|StringValue $characterMask
 */
public function trim($characterMask = self::TRIM_MASK): StringsArray;
```

### StringsArray::trimRight

```php
<?php
/**
 * @param string|StringValue $characterMask
 */
public function trimRight($characterMask = self::TRIM_MASK): StringsArray;
```

### StringsArray::trimLeft

```php
<?php
/**
 * @param string|StringValue $characterMask
 */
public function trimLeft($characterMask = self::TRIM_MASK): StringsArray;
```

### StringsArray::lower

```php
<?php

public function lower(): StringsArray;
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
  0 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'sometimes',
  )),
  1 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'i',
  )),
  2 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'wanna',
  )),
  3 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'scream!',
  )),
)
```

### StringsArray::upper

```php
<?php

public function upper(): StringsArray;
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
  0 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'IT`S SO QUIET',
  )),
  1 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'AND PEACEFUL',
  )),
)
```

### StringsArray::lowerFirst

```php
<?php

public function lowerFirst(): StringsArray;
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
  0 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'camelCaseMethod',
  )),
  1 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'anotherCamel',
  )),
)
```

### StringsArray::upperFirst

```php
<?php

public function upperFirst(): StringsArray;
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

public function upperWords(): StringsArray;
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
 * @param string|StringValue $string
 */
public function padRight(int $length, $string = ' '): StringsArray;
```

### StringsArray::padLeft

```php
<?php
/**
 * @param string|StringValue $string
 */
public function padLeft(int $length, $string = ' '): StringsArray;
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
  0 => 
  GW\Value\PlainString::__set_state(array(
     'value' => '-------------one',
  )),
  1 => 
  GW\Value\PlainString::__set_state(array(
     'value' => '-------------two',
  )),
  2 => 
  GW\Value\PlainString::__set_state(array(
     'value' => '-----------three',
  )),
)
```

### StringsArray::padBoth

```php
<?php
/**
 * @param string|StringValue $string
 */
public function padBoth(int $length, $string = ' '): StringsArray;
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
  0 => 
  GW\Value\PlainString::__set_state(array(
     'value' => '----------one-----------',
  )),
  1 => 
  GW\Value\PlainString::__set_state(array(
     'value' => '----------two-----------',
  )),
  2 => 
  GW\Value\PlainString::__set_state(array(
     'value' => '---------three----------',
  )),
)
```

### StringsArray::replace

```php
<?php
/**
 * @param string|StringValue $search
 * @param string|StringValue $replace
 */
public function replace($search, $replace): StringsArray;
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
  0 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'One!!!',
  )),
  1 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'Two!!!',
  )),
  2 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'Three!!!',
  )),
)
```

### StringsArray::replaceAll

```php
<?php
/**
 * @return StringsArray
 * @param array<int,string>|ArrayValue<string> $search
 * @param string|StringValue $replace
 */
public function replaceAll($search, $replace): StringsArray;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['One?', 'Two!', 'Three!?']);

var_export($text->replaceAll(['?', '!'], '.')->toArray());
```

```
array (
  0 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'One.',
  )),
  1 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'Two.',
  )),
  2 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'Three..',
  )),
)
```

### StringsArray::replacePairs

```php
<?php
/**
 * @return StringsArray
 * @param array<string,string>|AssocValue<string,string> $pairs
 */
public function replacePairs($pairs): StringsArray;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['One?', 'Two!', 'Three!?']);

var_export($text->replacePairs(['One' => 'Eleven', 'Two' => 'Twelve'])->toArray());
```

```
array (
  0 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'Eleven?',
  )),
  1 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'Twelve!',
  )),
  2 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'Three!?',
  )),
)
```

### StringsArray::replacePattern

```php
<?php
/**
 * @param string|StringValue $pattern
 * @param string|StringValue $replacement
 */
public function replacePattern($pattern, $replacement): StringsArray;
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
  0 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'Pl**s*',
  )),
  1 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'c*ns*r',
  )),
  2 => 
  GW\Value\PlainString::__set_state(array(
     'value' => '*ll',
  )),
  3 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'v*w*ls!',
  )),
)
```

### StringsArray::replacePatternCallback

```php
<?php
/**
 * @param string|StringValue $pattern
 */
public function replacePatternCallback($pattern, callable $callback): StringsArray;
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
  0 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'Pl(e)(a)s(e)',
  )),
  1 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'c(e)ns(o)r',
  )),
  2 => 
  GW\Value\PlainString::__set_state(array(
     'value' => '(a)ll',
  )),
  3 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'v(o)w(e)ls!',
  )),
)
```

### StringsArray::truncate

```php
<?php
/**
 * @param string|StringValue $postfix
 */
public function truncate(int $length, $postfix = '...'): StringsArray;
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
  0 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'short',
  )),
  1 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'quite~~',
  )),
  2 => 
  GW\Value\PlainString::__set_state(array(
     'value' => 'very ~~',
  )),
)
```

### StringsArray::substring

```php
<?php

public function substring(int $start, ?int $length = null): StringsArray;
```

### StringsArray::postfix

```php
<?php
/**
 * @param string|StringValue $other
 */
public function postfix($other): StringsArray;
```

### StringsArray::prefix

```php
<?php
/**
 * @param string|StringValue $other
 */
public function prefix($other): StringsArray;
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
 * @return AssocValue<int, StringValue>
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
        ->mapKeys(function (int $oldKey, string $person): string {
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

### StringsArray::chunk

```php
<?php
/**
 * @phpstan-return ArrayValue<array<int, StringValue>>
 */
public function chunk(int $size): ArrayValue;
```

### StringsArray::toStringsArray

```php
<?php

public function toStringsArray(): StringsArray;
```

### StringsArray::isEmpty

```php
<?php

public function isEmpty(): bool;
```

### StringsArray::getIterator

*(definition not available)*
### StringsArray::length

```php
<?php

public function length(): int;
```

### StringsArray::position

```php
<?php
/**
 * @param string|StringValue $needle
 */
public function position($needle): ?int;
```

### StringsArray::positionLast

```php
<?php
/**
 * @param string|StringValue $needle
 */
public function positionLast($needle): ?int;
```

### StringsArray::matchAllPatterns

```php
<?php
/**
 * @param string|StringValue $pattern
 * @return ArrayValue<array<int, string>>
 */
public function matchAllPatterns($pattern): ArrayValue;
```

### StringsArray::matchPatterns

```php
<?php
/**
 * @param string|StringValue $pattern
 * @return StringsArray
 */
public function matchPatterns($pattern): StringsArray;
```

### StringsArray::isMatching

```php
<?php
/**
 * @param string|StringValue $pattern
 */
public function isMatching($pattern): bool;
```

### StringsArray::startsWith

```php
<?php
/**
 * @param string|StringValue $pattern
 */
public function startsWith($pattern): bool;
```

### StringsArray::endsWith

```php
<?php
/**
 * @param string|StringValue $pattern
 */
public function endsWith($pattern): bool;
```

### StringsArray::splitByPattern

```php
<?php
/**
 * @return StringsArray
 * @param string|StringValue $pattern
 */
public function splitByPattern($pattern): StringsArray;
```

### StringsArray::explode

```php
<?php
/**
 * @return StringsArray
 * @param string|StringValue $delimiter
 */
public function explode($delimiter): StringsArray;
```

### StringsArray::contains

```php
<?php
/**
 * @param string|StringValue $substring
 */
public function contains($substring): bool;
```

### StringsArray::toString

```php
<?php

public function toString(): string;
```

### StringsArray::__toString

```php
<?php

public function __toString(): string;
```


## IterableValue

### IterableValue::each

```php
<?php
/**
 * @phpstan-param callable(TValue):void $callback
 * @phpstan-return IterableValue<TKey, TValue>
 */
public function each(callable $callback): IterableValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::iterable(['a', 'b', 'c']);
$mapped = $array->each(function (string $letter): void {
    echo $letter;
});
```

```
abc
```

### IterableValue::filter

```php
<?php
/**
 * @phpstan-param callable(TValue):bool $filter
 * @phpstan-return IterableValue<TKey, TValue>
 */
public function filter(callable $filter): IterableValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$range = static function (int $start, int $end): iterable {
    for ($i = $start; $i <= $end; $i++) {
        yield $i;
    }
};

$array = Wrap::iterable($range(1, 4));
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

### IterableValue::filterEmpty

```php
<?php
/**
 * @phpstan-return IterableValue<TKey, TValue>
 */
public function filterEmpty(): IterableValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::iterable(['a', '', 'b', 'c']);
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

### IterableValue::map

```php
<?php
/**
 * @template TNewValue
 * @param callable(TValue,TKey $key):TNewValue $transformer
 * @phpstan-return IterableValue<TKey, TNewValue>
 */
public function map(callable $transformer): IterableValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::iterable(['a', 'b', 'c']);
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

### IterableValue::flatMap

```php
<?php
/**
 * @phpstan-template TNewValue
 * @phpstan-param callable(TValue):iterable<TNewValue> $transformer
 * @phpstan-return IterableValue<TKey, TNewValue>
 */
public function flatMap(callable $transformer): IterableValue;
```

### IterableValue::join

```php
<?php
/**
 * @phpstan-param iterable<TKey, TValue> $other
 * @phpstan-return IterableValue<TKey, TValue>
 */
public function join(iterable $other): IterableValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$range = function (int $start, int $end): iterable {
    for ($i = $start; $i <= $end; $i++) {
        yield $i;
    }
};

$one = Wrap::iterable($range(1, 3));
$two = Wrap::iterable($range(8, 10));

var_export($one->join($two)->join($range(11, 14))->toArray());
```

```
array (
  0 => 1,
  1 => 2,
  2 => 3,
  3 => 8,
  4 => 9,
  5 => 10,
  6 => 11,
  7 => 12,
  8 => 13,
  9 => 14,
)
```

### IterableValue::slice

```php
<?php
/**
 * @phpstan-return IterableValue<TKey, TValue>
 */
public function slice(int $offset, ?int $length = null): IterableValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$range = function (int $start, int $end): iterable {
    for ($i = $start; $i <= $end; $i++) {
        yield $i;
    }
};

var_export(Wrap::iterable($range(0, PHP_INT_MAX))->slice(2, 4)->toArray());
echo PHP_EOL;

var_export(Wrap::iterable($range(0, PHP_INT_MAX))->slice(0, 3)->toArray());
echo PHP_EOL;

var_export(Wrap::iterable($range(0, PHP_INT_MAX))->slice(5000, 2)->toArray());
echo PHP_EOL;
```

```
array (
  0 => 2,
  1 => 3,
  2 => 4,
  3 => 5,
)
array (
  0 => 0,
  1 => 1,
  2 => 2,
)
array (
  0 => 5000,
  1 => 5001,
)
```

### IterableValue::skip

```php
<?php
/**
 * @phpstan-return IterableValue<TKey, TValue>
 */
public function skip(int $length): IterableValue;
```

### IterableValue::take

```php
<?php
/**
 * @phpstan-return IterableValue<TKey, TValue>
 */
public function take(int $length): IterableValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$range = function (int $start, int $end): iterable {
    for ($i = $start; $i <= $end; $i++) {
        yield $i;
    }
};

var_export(Wrap::iterable($range(0, PHP_INT_MAX))->skip(2)->take(4)->toArray());
echo PHP_EOL;

var_export(Wrap::iterable($range(0, PHP_INT_MAX))->take(3)->toArray());
echo PHP_EOL;

var_export(Wrap::iterable($range(0, PHP_INT_MAX))->skip(5000)->take(2)->toArray());
echo PHP_EOL;
```

```
array (
  0 => 2,
  1 => 3,
  2 => 4,
  3 => 5,
)
array (
  0 => 0,
  1 => 1,
  2 => 2,
)
array (
  0 => 5000,
  1 => 5001,
)
```

### IterableValue::unique

```php
<?php
/**
 * @phpstan-param (callable(TValue,TValue):int) | null $comparator
 * @phpstan-return IterableValue<TKey, TValue>
 */
public function unique(?callable $comparator = null): IterableValue;
```

### IterableValue::reduce

```php
<?php
/**
 * @template TNewValue
 * @phpstan-param callable(TNewValue,TValue):TNewValue $transformer
 * @phpstan-param TNewValue $start
 * @phpstan-return TNewValue
 */
public function reduce(callable $transformer, $start);
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$prices = Wrap::iterable([10, 20, 50, 120]);

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

### IterableValue::notEmpty

```php
<?php
/**
 * @phpstan-return IterableValue<TKey, TValue>
 */
public function notEmpty(): IterableValue;
```

### IterableValue::unshift

```php
<?php
/**
 * @phpstan-param TValue $value
 * @phpstan-return IterableValue<TKey, TValue>
 */
public function unshift($value): IterableValue;
```

### IterableValue::push

```php
<?php
/**
 * @phpstan-param TValue $value
 * @phpstan-return IterableValue<TKey, TValue>
 */
public function push($value): IterableValue;
```

### IterableValue::diff

```php
<?php
/**
 * @phpstan-param ArrayValue<TValue> $other
 * @phpstan-param (callable(TValue,TValue):int) | null $comparator
 * @phpstan-return IterableValue<TKey, TValue>
 */
public function diff(ArrayValue $other, ?callable $comparator = null): IterableValue;
```

### IterableValue::intersect

```php
<?php
/**
 * @phpstan-param ArrayValue<TValue> $other
 * @phpstan-param (callable(TValue,TValue):int) | null $comparator
 * @phpstan-return IterableValue<TKey, TValue>
 */
public function intersect(ArrayValue $other, ?callable $comparator = null): IterableValue;
```

### IterableValue::first

```php
<?php
/**
 * @phpstan-return ?TValue
 */
public function first();
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$range = function (int $start, int $end): iterable {
    for ($i = $start; $i <= $end; $i++) {
        yield $i;
    }
};

$array = Wrap::iterable($range(1, PHP_INT_MAX));

echo 'first: ' . $array->first();
```

```
first: 1
```

### IterableValue::last

```php
<?php
/**
 * @phpstan-return ?TValue
 */
public function last();
```

### IterableValue::toArrayValue

```php
<?php
/**
 * @phpstan-return ArrayValue<TValue>
 */
public function toArrayValue(): ArrayValue;
```

### IterableValue::toAssocArray

```php
<?php
/**
 * @phpstan-return array<int|string, TValue>
 */
public function toAssocArray(): array;
```

### IterableValue::toAssocValue

```php
<?php
/**
 * @phpstan-return AssocValue<int|string, TValue>
 */
public function toAssocValue(): AssocValue;
```

### IterableValue::toArray

```php
<?php
/**
 * @phpstan-return TValue[]
 */
public function toArray(): array;
```

### IterableValue::chunk

```php
<?php
/**
 * @phpstan-return IterableValue<int, array<int, TValue>>
 */
public function chunk(int $size): IterableValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$array = Wrap::iterable([1, 2, 3, 4, 3, 4, 5, 6, 7, 8, 9]);

var_export($array->chunk(3)->toArray());
```

```
array (
  0 => 
  array (
    0 => 1,
    1 => 2,
    2 => 3,
  ),
  1 => 
  array (
    0 => 4,
    1 => 3,
    2 => 4,
  ),
  2 => 
  array (
    0 => 5,
    1 => 6,
    2 => 7,
  ),
  3 => 
  array (
    0 => 8,
    1 => 9,
  ),
)
```

### IterableValue::flatten

```php
<?php
/**
 * @phpstan-return IterableValue<TKey, TValue>
 */
public function flatten(): IterableValue;
```

### IterableValue::any

```php
<?php
/**
 * @param callable(TValue):bool $filter
 */
public function any(callable $filter): bool;
```

### IterableValue::every

```php
<?php
/**
 * @param callable(TValue):bool $filter
 */
public function every(callable $filter): bool;
```

### IterableValue::find

```php
<?php
/**
 * @param callable(TValue):bool $filter
 * @phpstan-return ?TValue
 */
public function find(callable $filter);
```

### IterableValue::findLast

```php
<?php
/**
 * @param callable(TValue):bool $filter
 * @phpstan-return ?TValue
 */
public function findLast(callable $filter);
```

### IterableValue::keys

```php
<?php
/**
 * @phpstan-return IterableValue<int, TKey>
 */
public function keys(): IterableValue;
```

#### Examples

```php
<?php

use GW\Value\Wrap;

$assoc = Wrap::iterable(['0' => 'zero', '1' => 'one']);

$keys = $assoc
    ->map(fn(string $val, int $key): string => $val)
    ->keys()
    ->toArray();

var_export($keys);

$pairs = [['0', 'zero'], ['1', 'one'], ['1', 'one one']];

$iterator = function () use ($pairs) {
    foreach ($pairs as [$key, $item]) {
        yield $key => $item;
    }
};

$assoc = Wrap::iterable($iterator());

$keys = $assoc
    ->map(fn(string $val, string $key): string => $val)
    ->keys()
    ->toArray();

var_export($keys);
```

```
array (
  0 => 0,
  1 => 1,
)array (
  0 => '0',
  1 => '1',
  2 => '1',
)
```

### IterableValue::getIterator

*(definition not available)*


