<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\CallConfigureWithArrayRule\Fixture;

use SomeClass;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SomeClass::class)
        ->call('configure', [
            'value' => [
                'nested',
            ],
        ]);
};
