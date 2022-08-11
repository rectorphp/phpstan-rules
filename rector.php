<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ClassPropertyAssignToConstructorPromotionRector::class);

    $rectorConfig->sets([
        DowngradeLevelSetList::DOWN_TO_PHP_74,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::NAMING
    ]);

    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->skip([
        // testdummy files
        '*/Fixture/*',
        '*/Source/*',

        StringClassNameToClassConstantRector::class => [
            __DIR__ . '/src/Rule/RequireRectorCategoryByGetNodeTypesRule.php',
        ]
    ]);

    $rectorConfig->importNames();
    $rectorConfig->parallel();
};
