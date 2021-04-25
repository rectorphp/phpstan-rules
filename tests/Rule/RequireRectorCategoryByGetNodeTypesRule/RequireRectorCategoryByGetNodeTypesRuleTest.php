<?php

declare(strict_types=1);

namespace Rector\RectorPHPStanRules\Tests\Rule\RequireRectorCategoryByGetNodeTypesRule;

use Iterator;
use PHPStan\Rules\Rule;
use Rector\RectorPHPStanRules\Rule\RequireRectorCategoryByGetNodeTypesRule;
use Rector\RectorPHPStanRules\Tests\Rule\RequireRectorCategoryByGetNodeTypesRule\Fixture\ClassMethod\ChangeSomethingRector;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

final class RequireRectorCategoryByGetNodeTypesRuleTest extends AbstractServiceAwareRuleTestCase
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
        $errorMessage = sprintf(
            RequireRectorCategoryByGetNodeTypesRule::ERROR_MESSAGE,
            ChangeSomethingRector::class,
            'ClassMethod',
            'String_'
        );

<<<<<<< HEAD
        yield [__DIR__ . '/Fixture/ClassMethod/ChangeSomethingRector.php', [[$errorMessage, 17]]];
=======
        yield [__DIR__ . '/Fixture/ClassMethod/ChangeSomethingRector.php', [[$errorMessage, 14]]];
>>>>>>> 2b831c8 (bump to next phpstan-extensions with type)
        yield [__DIR__ . '/Fixture/FunctionLike/SkipSubtypeRector.php', []];
        yield [__DIR__ . '/Fixture/ClassMethod/SkipInterface.php', []];
        yield [__DIR__ . '/Fixture/SkipAbstract.php', []];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            RequireRectorCategoryByGetNodeTypesRule::class,
            __DIR__ . '/config/configured_rule.neon'
        );
    }
}
