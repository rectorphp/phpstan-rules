<?php

declare(strict_types=1);

namespace Rector\RectorPHPStanRules\Tests\Rule\ForwardUnionTypeRule\Fixture;

final class SomeFunctionWithUnionType
{
    /**
     * @param int|string $value
     */
    public function run($value)
    {
    }
}
