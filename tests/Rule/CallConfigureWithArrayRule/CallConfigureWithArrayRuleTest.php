<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\CallConfigureWithArrayRule;

use Iterator;
use PHPStan\Rules\Rule;
use Rector\PHPStanRules\Rule\CallConfigureWithArrayRule;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

/**
 * @extends AbstractServiceAwareRuleTestCase<CallConfigureWithArrayRule>
 */
final class CallConfigureWithArrayRuleTest extends AbstractServiceAwareRuleTestCase
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
        yield [__DIR__ . '/Fixture/SkipCorrectConfig.php', []];
        yield [__DIR__ . '/Fixture/SkipCorrectConfigWithKey.php', []];

        yield [__DIR__ . '/Fixture/WrongConfig.php', [[CallConfigureWithArrayRule::ERROR_MESSAGE, 12]]];
        yield [__DIR__ . '/Fixture/WrongConfigTooMany.php', [[CallConfigureWithArrayRule::ERROR_MESSAGE, 12]]];
        yield [__DIR__ . '/Fixture/WrongConfigTooFew.php', [[CallConfigureWithArrayRule::ERROR_MESSAGE, 12]]];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            CallConfigureWithArrayRule::class,
            __DIR__ . '/config/configured_rule.neon'
        );
    }
}
