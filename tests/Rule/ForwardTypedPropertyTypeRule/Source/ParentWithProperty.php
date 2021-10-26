<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\ForwardTypedPropertyTypeRule\Source;

class ParentWithProperty
{
    /**
     * @var string|null
     */
    protected $line;
}
