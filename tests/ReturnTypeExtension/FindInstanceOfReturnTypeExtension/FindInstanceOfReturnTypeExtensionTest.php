<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\ReturnTypeExtension\FindInstanceOfReturnTypeExtension;

use Iterator;
use PHPStan\Testing\TypeInferenceTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class FindInstanceOfReturnTypeExtensionTest extends TypeInferenceTestCase
{
    public function dataAsserts(): Iterator
    {
        yield from $this->gatherAssertTypes(__DIR__ . '/data/find_instanceof.php.inc');
        yield from $this->gatherAssertTypes(__DIR__ . '/data/find_single_instanceof.php.inc');
    }

    #[DataProvider('dataAsserts')]
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
