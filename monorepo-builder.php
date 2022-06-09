<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;

return static function (MBConfig $mbConfig): void {
    $mbConfig->workers([
        // @see https://github.com/symplify/monorepo-builder#6-release-flow
        TagVersionReleaseWorker::class,
        PushTagReleaseWorker::class,
    ]);
};
