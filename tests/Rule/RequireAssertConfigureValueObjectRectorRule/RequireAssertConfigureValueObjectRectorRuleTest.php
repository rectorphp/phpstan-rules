<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\RequireAssertConfigureValueObjectRectorRule;

use Iterator;
use PHPStan\Rules\Rule;
use Rector\PHPStanRules\Rule\RequireAssertConfigureValueObjectRectorRule;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

/**
 * @extends AbstractServiceAwareRuleTestCase<RequireAssertConfigureValueObjectRectorRule>
 */
final class RequireAssertConfigureValueObjectRectorRuleTest extends AbstractServiceAwareRuleTestCase
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
        yield [__DIR__ . '/Fixture/MissingConfigureWithAssert.php', [[RequireAssertConfigureValueObjectRectorRule::ERROR_MESSAGE, 20]]];

        yield [__DIR__ . '/Fixture/SkipConfigureWithAssert.php', []];
        yield [__DIR__ . '/Fixture/SkipConfigureWithAssertInstanceof.php', []];
        yield [__DIR__ . '/Fixture/SkipNoArray.php', []];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            RequireAssertConfigureValueObjectRectorRule::class,
            __DIR__ . '/config/configured_rule.neon'
        );
    }
}
