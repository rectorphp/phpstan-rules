<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\ForbiddenInterfacesTogetherRule\Fixture;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Contract\Rector\RectorInterface;

final class Mixture implements RectorInterface, FileProcessorInterface
{
}
