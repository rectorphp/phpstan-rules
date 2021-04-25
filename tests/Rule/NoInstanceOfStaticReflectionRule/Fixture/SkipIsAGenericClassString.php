<?php

declare(strict_types=1);

namespace Rector\RectorPHPStanRules\Tests\Rule\NoInstanceOfStaticReflectionRule\Fixture;

use PhpParser\Node;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class SkipIsAGenericClassString
{
    /**
     * @template T of Node
     * @param class-string<T> $type
     */
    public function findParentType(Node $parent, string $type)
    {
        do {
            if (is_a($parent, $type, true)) {
                return $parent;
            }
        } while ($parent = $parent->getAttribute(AttributeKey::PARENT_NODE));
    }
}
