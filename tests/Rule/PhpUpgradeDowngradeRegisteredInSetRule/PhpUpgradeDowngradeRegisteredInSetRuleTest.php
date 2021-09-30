<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\PhpUpgradeDowngradeRegisteredInSetRule;

use Iterator;
use PHPStan\Rules\Rule;
use Rector\PHPStanRules\Rule\PhpUpgradeDowngradeRegisteredInSetRule;
use Rector\PHPStanRules\Tests\Rule\PhpUpgradeDowngradeRegisteredInSetRule\Fixture\DowngradePhp80\SomePhpFeature2Rector;
use Rector\PHPStanRules\Tests\Rule\PhpUpgradeDowngradeRegisteredInSetRule\Fixture\Php80\SomePhpFeatureRector;
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
        yield [__DIR__ . '/Fixture/Php80/SkipConfigurableRector.php', []];

        $errorMessage = sprintf(
            PhpUpgradeDowngradeRegisteredInSetRule::ERROR_MESSAGE,
            SomePhpFeatureRector::class,
            'php80.php'
        );
        yield [__DIR__ . '/Fixture/Php80/SomePhpFeatureRector.php', [[$errorMessage, 10]]];

        $errorMessage = sprintf(
            PhpUpgradeDowngradeRegisteredInSetRule::ERROR_MESSAGE,
            SomePhpFeature2Rector::class,
            'downgrade-php80.php'
        );
        yield [__DIR__ . '/Fixture/DowngradePhp80/SomePhpFeature2Rector.php', [[$errorMessage, 10]]];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            PhpUpgradeDowngradeRegisteredInSetRule::class,
            __DIR__ . '/config/configured_rule.neon'
        );
    }
}
