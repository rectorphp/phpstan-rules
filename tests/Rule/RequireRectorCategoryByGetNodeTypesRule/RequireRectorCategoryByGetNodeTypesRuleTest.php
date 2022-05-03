<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\RequireRectorCategoryByGetNodeTypesRule;

use Iterator;
use PHPStan\Rules\Rule;
use Rector\PHPStanRules\Rule\RequireRectorCategoryByGetNodeTypesRule;
use Rector\PHPStanRules\Tests\Rule\RequireRectorCategoryByGetNodeTypesRule\Fixture\ClassMethod\ChangeSomethingRector;

/**
 * @extends \PHPStan\Testing\RuleTestCase<RequireRectorCategoryByGetNodeTypesRule>
 */
final class RequireRectorCategoryByGetNodeTypesRuleTest extends \PHPStan\Testing\RuleTestCase
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
        $errorMessage = sprintf(
            RequireRectorCategoryByGetNodeTypesRule::ERROR_MESSAGE,
            ChangeSomethingRector::class,
            'ClassMethod',
            'String_'
        );

        yield [__DIR__ . '/Fixture/ClassMethod/ChangeSomethingRector.php', [[$errorMessage, 17]]];
        yield [__DIR__ . '/Fixture/FunctionLike/SkipSubtypeRector.php', []];
        yield [__DIR__ . '/Fixture/ClassMethod/SkipInterface.php', []];
        yield [__DIR__ . '/Fixture/SkipAbstract.php', []];
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(RequireRectorCategoryByGetNodeTypesRule::class);
    }
}
