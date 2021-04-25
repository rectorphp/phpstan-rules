<?php

declare(strict_types=1);

namespace Rector\RectorPHPStanRules\Tests\Rule\RequireRectorCategoryByGetNodeTypesRule\Fixture;

use PhpParser\Node\Stmt\ClassMethod;

abstract class SkipAbstract
{
    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }
}
