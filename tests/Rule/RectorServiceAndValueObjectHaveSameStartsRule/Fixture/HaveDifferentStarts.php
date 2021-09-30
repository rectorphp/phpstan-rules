<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule\Fixture;

use Rector\PHPStanRules\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule\Source\ChangeMethodVisibilityRector;
use Rector\PHPStanRules\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule\Source\ConfigureValueObject;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ChangeMethodVisibilityRector::class)
        ->call('configure', [[
            ChangeMethodVisibilityRector::METHOD_VISIBILITIES => ValueObjectInliner::inline([
                new ConfigureValueObject(),
                new ConfigureValueObject(),
            ]),
        ]]);
};
