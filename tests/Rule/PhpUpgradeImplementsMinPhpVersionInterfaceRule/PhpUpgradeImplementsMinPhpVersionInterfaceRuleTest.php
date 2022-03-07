<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\PhpUpgradeImplementsMinPhpVersionInterfaceRule;

use Iterator;
use PHPStan\Rules\Rule;
use Rector\PHPStanRules\Rule\PhpUpgradeImplementsMinPhpVersionInterfaceRule;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

/**
 * @extends AbstractServiceAwareRuleTestCase<PhpUpgradeImplementsMinPhpVersionInterfaceRule>
 */
final class PhpUpgradeImplementsMinPhpVersionInterfaceRuleTest extends AbstractServiceAwareRuleTestCase
{
    /**
     * @dataProvider provideData()
     * @param mixed[] $expectedErrorsWithLines
     */
    public function testRule(string $filePath, array $expectedErrorsWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorsWithLines);
    }

    /**
     * @return Iterator<string[]|array<int, mixed[]>>
     */
    public function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/SkipDowngradeRector.php', []];
        yield [__DIR__ . '/Fixture/SkipAlreadyImplementsMinPhpVersionRector.php', []];
        yield [__DIR__ . '/Fixture/SomePhpFeatureRector.php', [
            [
                sprintf(
                    PhpUpgradeImplementsMinPhpVersionInterfaceRule::ERROR_MESSAGE,
                    'Rector\Php80\Rector\Class_\SomePhpFeatureRector'
                ),
                7,
            ],
        ]];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            PhpUpgradeImplementsMinPhpVersionInterfaceRule::class,
            __DIR__ . '/config/configured_rule.neon'
        );
    }
}
