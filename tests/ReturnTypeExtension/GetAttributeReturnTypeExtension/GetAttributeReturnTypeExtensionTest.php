<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Tests\ReturnTypeExtension\GetAttributeReturnTypeExtension;

use Iterator;
use PHPStan\Testing\TypeInferenceTestCase;

/**
 * @see \Rector\PHPStanRules\ReturnTypeExtension\GetAttributeReturnTypeExtension
 */
final class GetAttributeReturnTypeExtensionTest extends TypeInferenceTestCase
{
    public function dataAsserts(): Iterator
    {
        yield from $this->gatherAssertTypes(__DIR__ . '/data/get_parent_node.php.inc');
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
