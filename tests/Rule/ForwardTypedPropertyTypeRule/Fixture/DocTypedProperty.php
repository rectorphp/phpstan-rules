<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\ForwardTypedPropertyTypeRule\Fixture;

final class DocTypedProperty
{
    /**
     * @var string
     */
    public $name;

    public function rename()
    {
        return $this->name;
    }
}
