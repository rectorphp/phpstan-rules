<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\ForbiddenComplexArrayConfigInSetRule;

use Iterator;
use PHPStan\Rules\Rule;
use Rector\PHPStanRules\Rule\ForbiddenComplexArrayConfigInSetRule;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

/**
 * @extends AbstractServiceAwareRuleTestCase<ForbiddenComplexArrayConfigInSetRule>
 */
final class ForbiddenComplexArrayConfigInSetRuleTest extends AbstractServiceAwareRuleTestCase
{
    /**
     * @dataProvider provideData()
     * @param array<string|int> $expectedErrorMessagesWithLines
     */
    public function testRule(string $filePath, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorMessagesWithLines);
    }

    public function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/ComplexConfig.php', [[ForbiddenComplexArrayConfigInSetRule::ERROR_MESSAGE, 15]]];

        yield [__DIR__ . '/Fixture/SkipSimpleConfig.php', []];
        yield [__DIR__ . '/Fixture/SkipExtension.php', []];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            ForbiddenComplexArrayConfigInSetRule::class,
            __DIR__ . '/config/configured_rule.neon'
        );
    }
}
