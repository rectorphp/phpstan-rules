<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\FileProcessorRectorConstructorContractRule\Fixture;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;

final class WrongFileProcessor implements FileProcessorInterface
{
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
