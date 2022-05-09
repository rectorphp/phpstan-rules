<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule\Fixture;

use Rector\Config\RectorConfig;
use Rector\PHPStanRules\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule\Source\ChangeMethodVisibilityRector;
use Rector\PHPStanRules\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule\Source\ConfigureValueObject;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ChangeMethodVisibilityRector::class, [
        new ConfigureValueObject(),
        new ConfigureValueObject(),
    ]);
};
