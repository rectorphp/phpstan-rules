<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\ReturnTypeExtension\NameResolverReturnTypeExtension;

use PHPStan\Testing\TypeInferenceTestCase;

final class NameResolverReturnTypeExtensionTest extends TypeInferenceTestCase
{
    /**
     * @return iterable<string, mixed[]>
     */
    public function dataAsserts(): iterable
    {
        yield from $this->gatherAssertTypes(__DIR__ . '/data/get_name_class_method.php.inc');
    }

    /**
     * @dataProvider dataAsserts()
     * @param mixed ...$args
     */
    public function testAsserts(string $assertType, string $file, ...$args): void
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
