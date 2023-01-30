<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\NodeAnalyzer;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;
use PhpParser\NodeFinder;
use PHPStan\Type\ObjectType;

final class SymfonyConfigRectorValueObjectResolver
{
    public function __construct(
        private readonly NodeFinder $nodeFinder
    ) {
    }

    public function resolveFromRuleWithConfigurationMethodCall(MethodCall $methodCall): ?ObjectType
    {
        $node = $this->nodeFinder->findFirstInstanceOf($methodCall->getArgs(), New_::class);
        if (! $node instanceof New_) {
            return null;
        }

        if (! $node->class instanceof Name) {
            return null;
        }

        $className = $node->class->toString();
        return new ObjectType($className);
    }
}
