<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\ForwardTypedPropertyTypeRule\Fixture;

final class SkipArrayTypedProperty
{
    /**
     * @var string[]
     */
    public array $items;

    public function rename()
    {
        return $this->items;
    }
}
