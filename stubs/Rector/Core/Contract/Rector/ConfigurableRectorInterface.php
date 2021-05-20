<?php

declare(strict_types=1);

namespace Rector\Core\Contract\Rector;

if (class_exists('Rector\Core\Contract\Rector\ConfigurableRectorInterface')) {
    return;
}

interface ConfigurableRectorInterface
{
}
