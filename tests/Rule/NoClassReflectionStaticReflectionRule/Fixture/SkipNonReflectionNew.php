<?php

declare(strict_types=1);

namespace Rector\RectorPHPStanRules\Tests\Rule\NoClassReflectionStaticReflectionRule\Fixture;

use PHPStan\ShouldNotHappenException;

final class SkipNonReflectionNew
{
    public function check()
    {
        return new ShouldNotHappenException('Something');
    }
}
