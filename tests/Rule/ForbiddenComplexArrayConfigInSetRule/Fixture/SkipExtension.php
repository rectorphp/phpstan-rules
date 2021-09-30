<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('doctrine', [
        'orm' => [
            'mappings' => [
                [
                    'name' => 'DoctrineBehaviorsVersionable',
                    'type' => 'annotation',
                    'prefix' => 'Knp\DoctrineBehaviors\Versionable\Entity\\',
                    'dir' => __DIR__ . '/../../src/Entity',
                ],
            ],
        ],
    ]);
};
