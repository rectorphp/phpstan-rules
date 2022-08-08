<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\NodeAnalyzer;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeWithClassName;
use Rector\Config\RectorConfig;

final class SymfonyConfigMethodCallAnalyzer
{
    public function isRuleWithConfiguration(MethodCall $methodCall, Scope $scope): bool
    {
        $callerType = $scope->getType($methodCall->var);
        if (! $callerType instanceof TypeWithClassName) {
            return false;
        }

        $callerTypeObjectType = new ObjectType($callerType->getClassName());
        $serviceConfiguratorObjectType = new ObjectType(RectorConfig::class);

        if (! $serviceConfiguratorObjectType->isSuperTypeOf($callerTypeObjectType)->yes()) {
            return false;
        }

        if (! $methodCall->name instanceof Identifier) {
            return false;
        }

        return $methodCall->name->toString() === 'ruleWithConfiguration';
    }
}
