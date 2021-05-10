<?php

declare(strict_types=1);

namespace Rector\RectorPHPStanRules\Tests\Rule\ForwardUnionTypeRule;

use Iterator;
use PHPStan\Rules\Rule;
use Rector\RectorPHPStanRules\Rule\ForwardUnionTypeRule;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

/**
 * @extends AbstractServiceAwareRuleTestCase<ForwardUnionTypeRule>
 */
final class ForwardUnionTypeRuleTest extends AbstractServiceAwareRuleTestCase
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
        $errorMessage = ForwardUnionTypeRule::ERROR_MESSAGE;
        yield [__DIR__ . '/Fixture/SomeFunctionWithUnionType.php', [[$errorMessage, 12]]];

        yield [__DIR__ . '/Fixture/SkipNativeType.php', []];

        $errorMessage = ForwardUnionTypeRule::ERROR_MESSAGE;
        yield [__DIR__ . '/Fixture/SomeFunctionWithReturnUnionType.php', [[$errorMessage, 12]]];

        yield [__DIR__ . '/Fixture/SkipNativeReturnType.php', []];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            ForwardUnionTypeRule::class,
            __DIR__ . '/config/configured_rule.neon'
        );
    }
}
