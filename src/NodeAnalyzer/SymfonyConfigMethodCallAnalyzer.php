<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\NodeAnalyzer;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeWithClassName;
use Symplify\Astral\Naming\SimpleNameResolver;

final class SymfonyConfigMethodCallAnalyzer
{
    public function __construct(
        private SimpleNameResolver $simpleNameResolver
    ) {
    }

    public function isServicesSet(MethodCall $methodCall, Scope $scope): bool
    {
        $callerType = $scope->getType($methodCall->var);
        if (! $callerType instanceof TypeWithClassName) {
            return false;
        }

        $callerTypeObjectType = new ObjectType($callerType->getClassName());
        $serviceConfiguratorObjectType = new ObjectType('Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator');

        if (! $serviceConfiguratorObjectType->isSuperTypeOf($callerTypeObjectType)->yes()) {
            return false;
        }

        return $this->simpleNameResolver->isName($methodCall->name, 'set');
    }
}
