<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\ReturnTypeExtension;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;
use Rector\PHPStanRules\TypeResolver\GetNameMethodCallTypeResolver;
use Rector\Rector\AbstractRector;
use Rector\Rector\AbstractTemporaryRector;

/**
 * @see AbstractTemporaryRector::getName()
 *
 * These returns always strings for nodes with required names, e.g. for @see ClassMethod
 */
final class NameResolverReturnTypeAbstractRectorExtension implements DynamicMethodReturnTypeExtension
{
    public function __construct(
        private readonly GetNameMethodCallTypeResolver $getNameMethodCallTypeResolver
    ) {
    }

    public function getClass(): string
    {
        return AbstractRector::class;
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
        return $this->getNameMethodCallTypeResolver->resolveFromMethodCall($methodReflection, $methodCall, $scope);
    }
}
