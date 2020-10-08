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
