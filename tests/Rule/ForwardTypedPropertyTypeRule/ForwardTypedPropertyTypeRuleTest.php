<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\ForwardTypedPropertyTypeRule;

use Iterator;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Rector\PHPStanRules\Rule\ForwardTypedPropertyTypeRule;

/**
 * @extends RuleTestCase<ForwardTypedPropertyTypeRule>
 */
final class ForwardTypedPropertyTypeRuleTest extends RuleTestCase
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
        $errorMessage = ForwardTypedPropertyTypeRule::ERROR_MESSAGE;
        yield [__DIR__ . '/Fixture/DocTypedProperty.php', [[$errorMessage, 16]]];

        yield [__DIR__ . '/Fixture/SkipTypedProperty.php', []];
        yield [__DIR__ . '/Fixture/SkipArrayTypedProperty.php', []];
        yield [__DIR__ . '/Fixture/SkipNotLocalProperty.php', []];
        yield [__DIR__ . '/Fixture/SkipClosureAndResource.php', []];
    }

    /**
     * @return string[]
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(ForwardTypedPropertyTypeRule::class);
    }
}
