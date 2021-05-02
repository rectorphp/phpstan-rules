<?php

declare(strict_types=1);

namespace Rector\RectorPHPStanRules\Tests\Rule\FileProcessorRectorConstructorContractRule\Fixture;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\RectorPHPStanRules\Tests\Rule\FileProcessorRectorConstructorContractRule\Source\Contract\SomeRectorInterface;

final class CorrectFileProcessor implements FileProcessorInterface
{
    /**
     * @var SomeRectorInterface[]
     */
    private $someRectors;

    /**
     * @param SomeRectorInterface[] $someRectors
     */
    public function __construct(array $someRectors)
    {
        $this->someRectors = $someRectors;
    }

    public function run()
    {
    }
}
