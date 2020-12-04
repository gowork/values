<?php declare(strict_types=1);

namespace GW\Value\PHPStan;

use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use function get_class;

trait NotEmptyTypeRemover
{
    private function removeNull(Type $type): Type
    {
        echo get_class($type)."\n";
        if (!$type instanceof GenericObjectType) {
            return $type;
        }

        $types = $type->getTypes();

        return new GenericObjectType($type->getClassName(), [TypeCombinator::removeNull($types[0])]);
    }
}
