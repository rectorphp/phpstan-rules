<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\ForbiddenInterfacesTogetherRule\Fixture;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\ValueObject\Application\File;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class Mixture implements RectorInterface, FileProcessorInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
    }

    public function supports(File $file): bool
    {
    }

    public function process(array $files): void
    {
    }

    public function getSupportedFileExtensions(): array
    {
    }
}
