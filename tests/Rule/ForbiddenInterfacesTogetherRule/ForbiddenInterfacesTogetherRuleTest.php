<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\ForbiddenInterfacesTogetherRule;

use Iterator;
use PHPStan\Rules\Rule;
use Rector\PHPStanRules\Rule\ForbiddenInterfacesTogetherRule;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

/**
 * @extends \Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase<ForbiddenInterfacesTogetherRule>
 */
final class ForbiddenInterfacesTogetherRuleTest extends AbstractServiceAwareRuleTestCase
{
    /**
     * @dataProvider provideData()
     * @param array<string|string[]|int[]> $expectedErrorsWithLines
     */
    public function testRule(string $filePath, array $expectedErrorsWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorsWithLines);
    }

    public function provideData(): Iterator
    {
        $groupAsStrings = implode(
            '", "',
            ['Rector\Core\Contract\Processor\FileProcessorInterface', 'Rector\Core\Contract\Rector\RectorInterface']
        );
        $errorMessage = sprintf(ForbiddenInterfacesTogetherRule::ERROR_MESSAGE, $groupAsStrings);

        yield [__DIR__ . '/Fixture/Mixture.php', [[$errorMessage, 13]]];

        yield [__DIR__ . '/Fixture/SkipSeparated.php', []];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            ForbiddenInterfacesTogetherRule::class,
            __DIR__ . '/config/configured_rule.neon'
        );
    }
}
