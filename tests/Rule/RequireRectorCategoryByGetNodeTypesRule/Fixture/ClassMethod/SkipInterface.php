<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\RequireRectorCategoryByGetNodeTypesRule\Fixture\ClassMethod;

interface SkipInterface
{
    /**
     * @return string[]
     */
    public function getNodeTypes(): array;
}
