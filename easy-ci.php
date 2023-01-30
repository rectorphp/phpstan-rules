<?php

declare(strict_types=1);

return static function (EasyCI $easyCI): void {
    $easyCI->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);
};
