<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\TypeAnalyzer;

use PHPStan\Type\UnionType;

final class InlineableTypeAnalyzer
{
    public function isInlinableUnionType(UnionType $unionType): bool
    {
        foreach ($unionType->getTypes() as $unionedType) {
            if ($unionedType->isIterable()->yes()) {
                return false;
            }
        }

        return true;
    }
}
