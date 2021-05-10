<?php

declare(strict_types=1);

namespace Rector\RectorPHPStanRules\Tests\Rule\ForwardTypedPropertyTypeRule\Fixture;

final class SkipTypedProperty
{
    public string $name;

    public function rename()
    {
        return $this->name;
    }
}
