<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\FileProcessorRectorConstructorContractRule;

use Iterator;
use PHPStan\Rules\Rule;
use Rector\PHPStanRules\Rule\FileProcessorRectorConstructorContractRule;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

/**
 * @extends \Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase<FileProcessorRectorConstructorContractRule>
 */
final class FileProcessorRectorConstructorContractRuleTest extends AbstractServiceAwareRuleTestCase
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
        yield [__DIR__ . '/Fixture/CorrectFileProcessor.php', []];

        yield [__DIR__ . '/Fixture/WrongFileProcessor.php', [[FileProcessorRectorConstructorContractRule::ERROR_MESSAGE, 11]]];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            FileProcessorRectorConstructorContractRule::class,
            __DIR__ . '/config/configured_rule.neon'
        );
    }
}
