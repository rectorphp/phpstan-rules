<?php

declare(strict_types=1);

namespace Rector\Core\Rector;

use Rector\Core\Contract\Rector\RectorInterface;

if (class_exists('Rector\Core\Rector\AbstractRector')) {
    return;
}

abstract class AbstractRector implements RectorInterface
{
}
