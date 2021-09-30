<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeFinder;
use PHPStan\Type\ObjectType;
use Symplify\Astral\Naming\SimpleNameResolver;
use Symplify\Astral\ValueObject\AttributeKey;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

final class SymfonyConfigRectorValueObjectResolver
{
    /**
     * @var class-string
     */
    private const INLINE_CLASS_NAME = ValueObjectInliner::class;

    public function __construct(
        private NodeFinder $nodeFinder,
        private SimpleNameResolver $simpleNameResolver
    ) {
    }

    public function resolveFromSetMethodCall(MethodCall $methodCall): ObjectType|null
    {
        $parent = $methodCall->getAttribute(AttributeKey::PARENT);
        while (! $parent instanceof Expression) {
            $parent = $parent->getAttribute(AttributeKey::PARENT);
        }

        $inlineStaticCall = $this->nodeFinder->findFirst($parent, function (Node $node): bool {
            if (! $node instanceof StaticCall) {
                return false;
            }

            return $this->simpleNameResolver->isName($node->class, self::INLINE_CLASS_NAME);
        });

        if (! $inlineStaticCall instanceof StaticCall) {
            return null;
        }

        $new = $this->nodeFinder->findFirstInstanceOf($inlineStaticCall, New_::class);
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
