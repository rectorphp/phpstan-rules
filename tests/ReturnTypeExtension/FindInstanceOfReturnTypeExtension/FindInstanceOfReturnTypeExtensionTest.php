<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\ReturnTypeExtension\FindInstanceOfReturnTypeExtension;

use PHPStan\Testing\TypeInferenceTestCase;

final class FindInstanceOfReturnTypeExtensionTest extends TypeInferenceTestCase
{
    /**
     * @return iterable<string, mixed[]>
     */
    public function dataAsserts(): iterable
    {
        yield from $this->gatherAssertTypes(__DIR__ . '/data/find_instanceof.php');
        yield from $this->gatherAssertTypes(__DIR__ . '/data/find_single_instanceof.php');
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
