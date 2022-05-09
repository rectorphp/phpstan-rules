<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule\Fixture;

use Rector\Config\RectorConfig;
use Rector\PHPStanRules\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule\Source\ConfigureValueObjectWithInterface;
use Rector\Transform\Rector\StaticCall\StaticCallToFuncCallRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(StaticCallToFuncCallRector::class, [
        new ConfigureValueObjectWithInterface(),
    ]);
};
