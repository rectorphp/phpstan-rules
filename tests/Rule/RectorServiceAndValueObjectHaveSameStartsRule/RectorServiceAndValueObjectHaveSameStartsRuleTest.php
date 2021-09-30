<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule;

use Iterator;
use PHPStan\Rules\Rule;
use Rector\PHPStanRules\Rule\RectorServiceAndValueObjectHaveSameStartsRule;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

/**
 * @extends AbstractServiceAwareRuleTestCase<RectorServiceAndValueObjectHaveSameStartsRule>
 */
final class RectorServiceAndValueObjectHaveSameStartsRuleTest extends AbstractServiceAwareRuleTestCase
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
        yield [__DIR__ . '/Fixture/SkipHaveSameStarts.php', []];
        yield [__DIR__ . '/Fixture/SkipDifferentType.php', []];
        yield [__DIR__ . '/Fixture/SkipNoCall.php', []];
        yield [__DIR__ . '/Fixture/SkipNoCallConfigure.php', []];
        yield [__DIR__ . '/Fixture/SkipNoInlineValueObjects.php', []];
        yield [__DIR__ . '/Fixture/SkipConfigureValueObjectImplementsInterface.php', []];

        $errorMessage = sprintf(
            RectorServiceAndValueObjectHaveSameStartsRule::ERROR_MESSAGE,
            'ConfigureValueObject',
            'ChangeMethodVisibility'
        );
        yield [__DIR__ . '/Fixture/HaveDifferentStarts.php', [[$errorMessage, 15]]];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            RectorServiceAndValueObjectHaveSameStartsRule::class,
            __DIR__ . '/config/configured_rule.neon'
        );
    }
}
