<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\ReturnTypeExtension\NameResolverReturnTypeExtension;

use Iterator;
use PHPStan\Testing\TypeInferenceTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class NameResolverReturnTypeExtensionTest extends TypeInferenceTestCase
{
    #[DataProvider('dataAsserts')]
    public function testAsserts(string $assertType, string $file, mixed ...$args): void
    {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    public static function dataAsserts(): Iterator
    {
        yield from self::gatherAssertTypes(__DIR__ . '/data/get_name_class_method.php.inc');
    }

    /**
     * @return string[]
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../../../config/extensions.neon'];
    }
}
