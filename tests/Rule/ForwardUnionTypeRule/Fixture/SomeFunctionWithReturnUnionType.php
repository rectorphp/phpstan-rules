<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\ForwardUnionTypeRule\Fixture;

final class SomeFunctionWithReturnUnionType
{
    /**
     * @return int|string
     */
    public function run($value)
    {
    }
}
