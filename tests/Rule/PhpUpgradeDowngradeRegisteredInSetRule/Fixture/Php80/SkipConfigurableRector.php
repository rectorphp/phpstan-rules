<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\PhpUpgradeDowngradeRegisteredInSetRule\Fixture\Php80;

use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class SkipConfigurableRector implements RectorInterface, ConfigurableRectorInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
    }

    public function configure(array $configuration): void
    {
    }
}
