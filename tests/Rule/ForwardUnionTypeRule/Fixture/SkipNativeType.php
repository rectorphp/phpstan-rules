<?php

declare(strict_types=1);

namespace Rector\RectorPHPStanRules\Tests\Rule\ForwardUnionTypeRule\Fixture;

final class SkipNativeType
{
    /**
     * @param int|string $value
     */
    public function run(int | string $value)
    {
    }
}
