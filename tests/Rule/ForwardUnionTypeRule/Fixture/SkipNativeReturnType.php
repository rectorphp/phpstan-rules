<?php

declare(strict_types=1);

namespace Rector\RectorPHPStanRules\Tests\Rule\ForwardUnionTypeRule\Fixture;

final class SkipNativeReturnType
{
    /**
     * @return int|string
     */
    public function run(int | string $value): int | string
    {
    }
}
