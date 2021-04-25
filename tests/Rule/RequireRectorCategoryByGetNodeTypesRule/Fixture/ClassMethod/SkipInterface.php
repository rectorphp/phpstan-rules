<?php

declare(strict_types=1);

namespace Rector\RectorPHPStanRules\Tests\Rule\RequireRectorCategoryByGetNodeTypesRule\Fixture\ClassMethod;

interface SkipInterface
{
    public function getNodeTypes(): array;
}
