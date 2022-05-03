<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\NoClassReflectionStaticReflectionRule;

use Iterator;
use PHPStan\Rules\Rule;
use Rector\PHPStanRules\Rule\NoClassReflectionStaticReflectionRule;

/**
 * @extends \PHPStan\Testing\RuleTestCase<NoClassReflectionStaticReflectionRule>
 */
final class NoClassReflectionStaticReflectionRuleTest extends \PHPStan\Testing\RuleTestCase
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
        $errorMessage = NoClassReflectionStaticReflectionRule::ERROR_MESSAGE;
        yield [__DIR__ . '/Fixture/NewOnExternal.php', [[$errorMessage, 13]]];

        yield [__DIR__ . '/Fixture/SkipAllowedType.php', []];
        yield [__DIR__ . '/Fixture/SkipNonReflectionNew.php', []];
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(NoClassReflectionStaticReflectionRule::class);
    }
}
