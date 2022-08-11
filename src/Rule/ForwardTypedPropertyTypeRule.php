<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\Rule;
use PHPStan\Type\CallableType;
use PHPStan\Type\ClosureType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ResourceType;
use PHPStan\Type\Type;

/**
 * @see \Rector\PHPStanRules\Tests\Rule\ForwardTypedPropertyTypeRule\ForwardTypedPropertyTypeRuleTest
 *
 * @implements Rule<PropertyFetch>
 */
final class ForwardTypedPropertyTypeRule implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'This property type might be inlined to PHP. Do you have confidence it is correct? Put it here';

    public function getNodeType(): string
    {
        return PropertyFetch::class;
    }

    /**
     * @param PropertyFetch $node
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        // union types are supported since PHP 8+ only
        if (PHP_VERSION < 74000 && ! defined('PHPUNIT_COMPOSER_INSTALL')) {
            return [];
        }

        if (! $node->name instanceof Identifier) {
            return [];
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return [];
        }

        $propertyName = $node->name->toString();
        if (! $classReflection->hasNativeProperty($propertyName)) {
            return [];
        }

        $phpPropertyReflection = $classReflection->getNativeProperty($propertyName);

        // skip if not the same class
        if ($phpPropertyReflection->getDeclaringClass() !== $classReflection) {
            return [];
        }

        if (! $phpPropertyReflection->getNativeType() instanceof MixedType) {
            return [];
        }

        if (! $this->isLegalPropertyType($phpPropertyReflection->getPhpDocType())) {
            return [];
        }

        return [self::ERROR_MESSAGE];
    }

    private function isLegalPropertyType(Type $type): bool
    {
        if ($type instanceof ClosureType) {
            return false;
        }

        if ($type instanceof CallableType) {
            return false;
        }

        if ($type instanceof ResourceType) {
            return false;
        }

        return ! $type instanceof MixedType;
    }
}
