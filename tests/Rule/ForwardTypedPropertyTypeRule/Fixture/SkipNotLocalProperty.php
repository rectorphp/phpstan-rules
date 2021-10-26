<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\ForwardTypedPropertyTypeRule\Fixture;

use Rector\PHPStanRules\Tests\Rule\ForwardTypedPropertyTypeRule\Source\ParentWithProperty;

final class SkipNotLocalProperty extends ParentWithProperty
{
    public function rename()
    {
        $this->line = 'hey';
    }
}
