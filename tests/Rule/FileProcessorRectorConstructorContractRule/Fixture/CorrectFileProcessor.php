<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\FileProcessorRectorConstructorContractRule\Fixture;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
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
