<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\NodeAnalyzer;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\NodeFinder;
use PHPStan\Type\ObjectType;
use Symplify\Astral\Naming\SimpleNameResolver;

final class SymfonyConfigRectorValueObjectResolver
{
    public function __construct(
        private NodeFinder $nodeFinder,
        private SimpleNameResolver $simpleNameResolver
    ) {
    }

    public function resolveFromRuleWithConfigurationMethodCall(MethodCall $methodCall): ObjectType|null
    {
        $node = $this->nodeFinder->findFirstInstanceOf($methodCall->getArgs(), New_::class);
        if (! $node instanceof New_) {
            return null;
        }

        $className = $this->simpleNameResolver->getName($node->class);
        if ($className === null) {
            return null;
        }

        return new ObjectType($className);
    }
}
