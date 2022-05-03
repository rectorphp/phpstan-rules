<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\NoLeadingBackslashInNameRule;

use Iterator;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Rector\PHPStanRules\Rule\NoLeadingBackslashInNameRule;

/**
 * @extends RuleTestCase<NoLeadingBackslashInNameRule>
 */
final class NoLeadingBackslashInNameRuleTest extends RuleTestCase
{
    /**
     * @dataProvider provideData()
     * @param mixed[] $expectedErrorsWithLines
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

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(NoLeadingBackslashInNameRule::class);
    }
}
