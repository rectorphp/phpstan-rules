<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule\Fixture;

use Rector\PHPStanExtensions\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule\Source\ConfigureValueObject;
use Rector\Visibility\Rector\ClassMethod\ChangeMethodVisibilityRector;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (object $random): void {
    $random->set(ChangeMethodVisibilityRector::class)
        ->call('configure', [[
            ChangeMethodVisibilityRector::METHOD_VISIBILITIES => ValueObjectInliner::inline([
                new ConfigureValueObject(),
                new ConfigureValueObject(),
            ]),
        ]]);
};
