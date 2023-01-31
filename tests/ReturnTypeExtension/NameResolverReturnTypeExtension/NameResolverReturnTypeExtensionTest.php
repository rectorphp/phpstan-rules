<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\ReturnTypeExtension\NameResolverReturnTypeExtension;

use Iterator;
use PHPStan\Testing\TypeInferenceTestCase;

final class NameResolverReturnTypeExtensionTest extends TypeInferenceTestCase
{
    public function dataAsserts(): Iterator
    {
        yield from $this->gatherAssertTypes(__DIR__ . '/data/get_name_class_method.php.inc');
    }

    /**
     * @dataProvider dataAsserts()
     */
    public function testAsserts(string $assertType, string $file, mixed ...$args): void
    {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    /**
     * @return string[]
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../../../config/extensions.neon'];
    }
}
