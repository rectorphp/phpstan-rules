<?php

declare(strict_types=1);

namespace Rector\RectorPHPStanRules\Tests\Rule\RequireRectorCategoryByGetNodeTypesRule\Fixture\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ChangeSomethingRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [String_::class];
    }

    public function refactor(Node $node): ?Node
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
    }
}
