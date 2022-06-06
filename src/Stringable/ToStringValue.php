<?php declare(strict_types=1);

namespace GW\Value\Stringable;

use GW\Value\StringValue;
use GW\Value\Wrap;
use InvalidArgumentException;
use function is_scalar;

final class ToStringValue
{
    public function __invoke(mixed $string): StringValue
    {
        if (is_scalar($string)) {
            return Wrap::string((string)$string);
        }

        if ($string instanceof StringValue) {
            return $string;
        }

        throw new InvalidArgumentException('StringsValue can contain only StringValue');
    }
}
