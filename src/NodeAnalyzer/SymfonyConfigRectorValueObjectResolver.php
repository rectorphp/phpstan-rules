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
        private NodeFinder $nodeFinder
    ) {
    }

    public function resolveFromRuleWithConfigurationMethodCall(MethodCall $methodCall): ObjectType|null
    {
        $node = $this->nodeFinder->findFirstInstanceOf($methodCall->getArgs(), New_::class);
        if (! $node instanceof New_) {
            return null;
        }

<<<<<<< HEAD
        $className = $this->simpleNameResolver->getName($node->class);
        if ($className === null) {
=======
        if (! $new->class instanceof Name) {
>>>>>>> Remove dependency on simple name resolver, use nodes directly with known context
            return null;
        }

        $className = $new->class->toString();
        return new ObjectType($className);
    }
}
