<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\NoLeadingBackslashInNameRule;

use Iterator;
use PHPStan\Rules\Rule;
use Rector\PHPStanRules\Rule\NoLeadingBackslashInNameRule;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

/**
 * @extends AbstractServiceAwareRuleTestCase<NoLeadingBackslashInNameRule>
 */
final class NoLeadingBackslashInNameRuleTest extends AbstractServiceAwareRuleTestCase
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
        $errorMessage = NoLeadingBackslashInNameRule::ERROR_MESSAGE;
        yield [__DIR__ . '/Fixture/LeadingBackslashInName.php', [[$errorMessage, 13]]];

        yield [__DIR__ . '/Fixture/SkipNoBackslash.php', []];
        yield [__DIR__ . '/Fixture/SkipUseFullyQualified.php', []];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            NoLeadingBackslashInNameRule::class,
            __DIR__ . '/config/configured_rule.neon'
        );
    }
}
