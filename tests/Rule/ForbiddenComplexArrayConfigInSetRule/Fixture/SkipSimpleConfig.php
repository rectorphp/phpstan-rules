<?php

declare(strict_types=1);

use Rector\PHPStanRules\Tests\Rule\ForbiddenComplexArrayConfigInSetRule\Source\SomeRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(SomeRector::class)
        ->call('configure', [[
            SomeRector::METHOD_CALL_TO_SERVICES => [
                'Doctrine\Common\Persistence\ManagerRegistry' => 'simple',
            ],
        ]]);
};
