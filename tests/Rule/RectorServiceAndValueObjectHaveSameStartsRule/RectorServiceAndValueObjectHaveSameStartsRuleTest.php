<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule;

use Iterator;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\PHPStanRules\Rule\RectorServiceAndValueObjectHaveSameStartsRule;

final class RectorServiceAndValueObjectHaveSameStartsRuleTest extends RuleTestCase
{
    /**
     * @param mixed[] $expectedErrorsWithLines
     */
    #[DataProvider('provideData')]
    public function testRule(string $filePath, array $expectedErrorsWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorsWithLines);
    }

    public static function provideData(): Iterator
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
        yield [__DIR__ . '/Fixture/HaveDifferentStarts.php', [[$errorMessage, 12]]];
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(RectorServiceAndValueObjectHaveSameStartsRule::class);
    }
}
