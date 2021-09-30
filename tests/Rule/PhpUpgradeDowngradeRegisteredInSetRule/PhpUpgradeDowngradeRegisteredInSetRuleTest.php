<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\PhpUpgradeDowngradeRegisteredInSetRule;

use Iterator;
use PHPStan\Rules\Rule;
use Rector\PHPStanRules\Rule\PhpUpgradeDowngradeRegisteredInSetRule;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

/**
 * @extends AbstractServiceAwareRuleTestCase<PhpUpgradeDowngradeRegisteredInSetRule>
 */
final class PhpUpgradeDowngradeRegisteredInSetRuleTest extends AbstractServiceAwareRuleTestCase
{
    /**
     * @dataProvider provideData()
     * @param array<string|int> $expectedErrorMessagesWithLines
     */
    public function testRule(string $filePath, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorMessagesWithLines);
    }

    /**
     * @return Iterator<string[]|array<int, mixed[]>>
     */
    public function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/SkipSomePhpFeatureRector.php', []];

        $errorMessage = sprintf(
            PhpUpgradeDowngradeRegisteredInSetRule::ERROR_MESSAGE,
            'Rector\Php80\Rector\Class_\SomePhpFeatureRector',
            'php80'
        );
        yield [__DIR__ . '/Fixture/SomePhpFeatureRector.php', [[$errorMessage, 7]]];

        $errorMessage = sprintf(
            PhpUpgradeDowngradeRegisteredInSetRule::ERROR_MESSAGE,
            'Rector\DowngradePhp80\Rector\Class_\SomePhpFeature2Rector',
            'downgrade-php80'
        );
        yield [__DIR__ . '/Fixture/SomePhpFeature2Rector.php', [[$errorMessage, 7]]];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            PhpUpgradeDowngradeRegisteredInSetRule::class,
            __DIR__ . '/config/configured_rule.neon'
        );
    }
}
