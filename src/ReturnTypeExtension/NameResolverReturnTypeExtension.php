<?php

declare(strict_types=1);

namespace Rector\RectorPHPStanRules\ReturnTypeExtension;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;
use Rector\RectorPHPStanRules\TypeResolver\GetNameMethodCallTypeResolver;

/**
 * These returns always strings for nodes with required names, e.g. for @see ClassMethod
 */
final class NameResolverReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    /**
     * @var GetNameMethodCallTypeResolver
     */
    private $methodCallTypeResolver;

    public function __construct(GetNameMethodCallTypeResolver $methodCallTypeResolver)
    {
        $this->methodCallTypeResolver = $methodCallTypeResolver;
    }

    public function getClass(): string
    {
        return 'Rector\NodeNameResolver\NodeNameResolver';
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
