<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule\Fixture;

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\StaticCall\StaticCallToFuncCallRector;
use Rector\Transform\ValueObject\StaticCallToFuncCall;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(StaticCallToFuncCallRector::class, [
        new StaticCallToFuncCall('GuzzleHttp\Utils', 'setPath', 'GuzzleHttp\set_path'),
        new StaticCallToFuncCall('GuzzleHttp\Pool', 'batch', 'GuzzleHttp\Pool\batch'),
    ]);
};
