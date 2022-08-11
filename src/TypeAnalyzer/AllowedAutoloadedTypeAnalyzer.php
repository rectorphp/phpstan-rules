<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\TypeAnalyzer;

use DateTimeInterface;
use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\Node as PhpDocNode;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\Generic\GenericClassStringType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\Core\Util\StringUtils;

final class AllowedAutoloadedTypeAnalyzer
{
    /**
     * @see https://regex101.com/r/BBm9bf/1
     * @var string
     */
    private const AUTOLOADED_CLASS_PREFIX_REGEX = '#^(PhpParser|PHPStan|Rector|Reflection|Symfony\\\\Component\\\\Console)#';

    /**
     * @var array<string>
     */
    private const ALLOWED_CLASSES = [
        DateTimeInterface::class,
        'Symplify\SmartFileSystem\SmartFileInfo',
        Node::class,
        PhpDocNode::class,
    ];

    public function isAllowedType(Type $type): bool
    {
        if ($type instanceof UnionType) {
            foreach ($type->getTypes() as $unionedType) {
                if (! $this->isAllowedType($unionedType)) {
                    return false;
                }
            }

            return true;
        }

        if ($type instanceof ConstantStringType) {
            return $this->isAllowedClassString($type->getValue());
        }

        if ($type instanceof ObjectType) {
            return $this->isAllowedClassString($type->getClassName());
        }

        if ($type instanceof GenericClassStringType) {
            return $this->isAllowedType($type->getGenericType());
        }

        return false;
    }

    private function isAllowedClassString(string $value): bool
    {
        // autoloaded allowed type
        if (StringUtils::isMatch($value, self::AUTOLOADED_CLASS_PREFIX_REGEX)) {
            return true;
        }

        foreach (self::ALLOWED_CLASSES as $allowedClass) {
            if ($value === $allowedClass) {
                return true;
            }

            if (is_a($value, $allowedClass, true)) {
                return true;
            }
        }

        return false;
    }
}
