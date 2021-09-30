<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\ForbiddenComplexArrayConfigInSetRule\Source;

use Rector\Core\Contract\Rector\RectorInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class SomeRector implements RectorInterface
{
    /**
     * @var string
     */
    public const METHOD_CALL_TO_SERVICES = 'method_call_to_services';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('...', []);
    }
}
