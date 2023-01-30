<?php

declare(strict_types=1);

use PHPStan\Type\DynamicMethodReturnTypeExtension;
use Symplify\EasyCI\Config\EasyCIConfig;

return static function (EasyCIConfig $easyCIConfig): void {
    $easyCIConfig->paths([
        __DIR__ . '/src',
    ]);

    $easyCIConfig->typesToSkip([
        DynamicMethodReturnTypeExtension::class,
    ]);
};
