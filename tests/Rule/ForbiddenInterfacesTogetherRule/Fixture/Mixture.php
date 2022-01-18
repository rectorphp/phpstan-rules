<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\ForbiddenInterfacesTogetherRule\Fixture;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class Mixture implements RectorInterface, FileProcessorInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
    }

    public function supports(File $file, Configuration $configuration): bool
    {
    }

    public function process(File $file, Configuration $configuration): array
    {
    }

    public function getSupportedFileExtensions(): array
    {
    }
}
