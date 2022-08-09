<?php

declare(strict_types=1);

use PhpParser\Node\Stmt\ClassMethod;
use Rector\NodeNameResolver\NodeNameResolver;

final class SomeClass
{
    public function run(NodeNameResolver $nodeNameResolver, ClassMethod $classMethod): void
    {
        $methodName = $nodeNameResolver->getName($classMethod);

        // why is error here?
        \PHPStan\Testing\assertType('*ERROR*', $methodName);
    }
}
