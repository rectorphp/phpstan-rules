<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\FileProcessorRectorConstructorContractRule\Fixture;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\PHPStanRules\Tests\Rule\FileProcessorRectorConstructorContractRule\Source\Contract\SomeRectorInterface;

final class CorrectFileProcessor implements FileProcessorInterface
{
    /**
     * @param SomeRectorInterface[] $someRectors
     */
    public function __construct(
        private array $someRectors
    ) {
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
