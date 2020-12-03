<?php declare(strict_types=1);

namespace GW\Value\PHPStan;

use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

trait NotEmptyTypeRemover
{
    private function removeNull(Type $type): Type
    {
        if (!$type instanceof GenericObjectType) {
            return $type;
        }

        $types = $type->getTypes();

        return new GenericObjectType($type->getClassName(), [TypeCombinator::removeNull($types[0])]);
    }
}
