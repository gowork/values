<?php declare(strict_types=1);

namespace GW\Value\PHPStan;

use GW\Value\IterableValue;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

final class IterableValueNotEmptyExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return IterableValue::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'notEmpty' || $methodReflection->getName() === 'filterEmpty';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): Type {
        return $this->removeNull($scope->getType($methodCall->var));
    }

    private function removeNull(Type $type): Type
    {
        if (!$type instanceof GenericObjectType) {
            return $type;
        }

        $types = $type->getTypes();

        return new GenericObjectType($type->getClassName(), [$types[0], TypeCombinator::removeNull($types[1])]);
    }
}
