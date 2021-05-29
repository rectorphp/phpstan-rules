<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\ForbiddenInterfacesTogetherRule\Fixture;

use Rector\Core\Contract\Rector\RectorInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class SkipSeparated implements RectorInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
    }
}
