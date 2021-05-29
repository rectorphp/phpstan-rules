<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\FileProcessorRectorConstructorContractRule\Fixture;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;

final class WrongFileProcessor implements FileProcessorInterface
{
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
