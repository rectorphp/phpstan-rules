<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\Rule\ForwardTypedPropertyTypeRule\Fixture;

final class SkipClosureAndResource
{
    /**
     * @var resource
     */
    private $stdErr;

    /**
     * @var callable(mixed[]) : void
     */
    private $onData;
}