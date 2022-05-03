<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\ForwardUnionTypeRule;

use Iterator;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Rector\PHPStanRules\Rule\ForwardUnionTypeRule;

/**
 * @extends RuleTestCase<ForwardUnionTypeRule>
 */
final class ForwardUnionTypeRuleTest extends RuleTestCase
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
        $errorMessage = ForwardUnionTypeRule::ERROR_MESSAGE;
        yield [__DIR__ . '/Fixture/SomeFunctionWithUnionType.php', [[$errorMessage, 12]]];

        yield [__DIR__ . '/Fixture/SkipNativeType.php', []];

        $errorMessage = ForwardUnionTypeRule::ERROR_MESSAGE;
        yield [__DIR__ . '/Fixture/SomeFunctionWithReturnUnionType.php', [[$errorMessage, 12]]];

        yield [__DIR__ . '/Fixture/SkipNativeReturnType.php', []];
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(ForwardUnionTypeRule::class);
    }
}
