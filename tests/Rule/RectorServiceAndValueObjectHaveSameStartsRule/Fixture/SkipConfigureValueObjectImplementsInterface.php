<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule\Fixture;

use Rector\PHPStanRules\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule\Source\ConfigureValueObjectWithInterface;
use Rector\Transform\Rector\StaticCall\StaticCallToFuncCallRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(StaticCallToFuncCallRector::class)
        ->configure([
            new ConfigureValueObjectWithInterface(),
        ]);
};
