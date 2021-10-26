<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\ForwardTypedPropertyTypeRule;

use Iterator;
use PHPStan\Rules\Rule;
use Rector\PHPStanRules\Rule\ForwardTypedPropertyTypeRule;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

/**
 * @extends AbstractServiceAwareRuleTestCase<ForwardTypedPropertyTypeRule>
 */
final class ForwardTypedPropertyTypeRuleTest extends AbstractServiceAwareRuleTestCase
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
        $errorMessage = ForwardTypedPropertyTypeRule::ERROR_MESSAGE;
        yield [__DIR__ . '/Fixture/DocTypedProperty.php', [[$errorMessage, 16]]];

        yield [__DIR__ . '/Fixture/SkipTypedProperty.php', []];
        yield [__DIR__ . '/Fixture/SkipArrayTypedProperty.php', []];
        yield [__DIR__ . '/Fixture/SkipNotLocalProperty.php', []];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            ForwardTypedPropertyTypeRule::class,
            __DIR__ . '/config/configured_rule.neon'
        );
    }
}
