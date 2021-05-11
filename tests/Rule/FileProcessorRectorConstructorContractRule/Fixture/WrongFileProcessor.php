<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\FileProcessorRectorConstructorContractRule\Fixture;

use Rector\Core\Contract\Processor\FileProcessorInterface;

final class WrongFileProcessor implements FileProcessorInterface
{
    public function run()
    {
    }
}
