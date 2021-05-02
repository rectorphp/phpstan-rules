<?php

declare(strict_types=1);

namespace Rector\RectorPHPStanRules\Tests\Rule\FileProcessorRectorConstructorContractRule;

use Iterator;
use PHPStan\Rules\Rule;
use Rector\RectorPHPStanRules\Rule\FileProcessorRectorConstructorContractRule;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

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

        $errorMessage = FileProcessorRectorConstructorContractRule::ERROR_MESSAGE;
        yield [__DIR__ . '/Fixture/WrongFileProcessor.php', [[$errorMessage, 9]]];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            FileProcessorRectorConstructorContractRule::class,
            __DIR__ . '/config/configured_rule.neon'
        );
    }
}
