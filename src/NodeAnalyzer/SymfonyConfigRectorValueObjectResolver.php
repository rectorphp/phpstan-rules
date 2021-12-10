<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\NodeAnalyzer;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\NodeFinder;
use PHPStan\Type\ObjectType;
use Symplify\Astral\Naming\SimpleNameResolver;
use Symplify\Astral\ValueObject\AttributeKey;

final class SymfonyConfigRectorValueObjectResolver
{
    public function __construct(
        private NodeFinder $nodeFinder,
        private SimpleNameResolver $simpleNameResolver
    ) {
    }

    public function resolveFromSetMethodCall(MethodCall $methodCall): ObjectType|null
    {
        $parent = $methodCall->getAttribute(AttributeKey::PARENT);
        while ($parent instanceof MethodCall) {
            if ($this->simpleNameResolver->isName($parent->name, 'configure')) {
                break;
            }

            $parent = $parent->getAttribute(AttributeKey::PARENT);
        }

        if (! $parent instanceof MethodCall) {
            return null;
        }

        $new = $this->nodeFinder->findFirstInstanceOf($parent->args, New_::class);
        if (! $new instanceof New_) {
            return null;
        }

        $className = $this->simpleNameResolver->getName($new->class);
        if ($className === null) {
            return null;
        }

        return new ObjectType($className);
    }
}
