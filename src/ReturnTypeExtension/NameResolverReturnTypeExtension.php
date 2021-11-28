<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\ReturnTypeExtension;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PHPStanRules\TypeResolver\GetNameMethodCallTypeResolver;

/**
 * These returns always strings for nodes with required names, e.g. for @see ClassMethod
 */
final class NameResolverReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function __construct(
        private GetNameMethodCallTypeResolver $methodCallTypeResolver
    ) {
    }

    public function getClass(): string
    {
        return NodeNameResolver::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'getName';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): Type {
        return $this->methodCallTypeResolver->resolveFromMethodCall($methodReflection, $methodCall, $scope);
    }
}
