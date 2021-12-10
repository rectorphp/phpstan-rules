<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule\Fixture;

use Rector\Visibility\Rector\ClassMethod\ChangeMethodVisibilityRector;

return static function (object $random): void {
    $random->set(ChangeMethodVisibilityRector::class)
        ->configure([
            new ConfigureValueObject(),
            new ConfigureValueObject(),
        ]);
};
