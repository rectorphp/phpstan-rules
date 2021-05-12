<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\MixedType;
use Symplify\Astral\Naming\SimpleNameResolver;
use Symplify\EasyTesting\PHPUnit\StaticPHPUnitEnvironment;
use Symplify\PHPStanRules\Rules\AbstractSymplifyRule;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\PHPStanRules\Tests\Rule\ForwardTypedPropertyTypeRule\ForwardTypedPropertyTypeRuleTest
 */
final class ForwardTypedPropertyTypeRule extends AbstractSymplifyRule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'This property type might be inlined to PHP. Do you have confidence it is correct? Put it here';

    /**
     * @var SimpleNameResolver
     */
    private $simpleNameResolver;

    public function __construct(SimpleNameResolver $simpleNameResolver)
    {
        $this->simpleNameResolver = $simpleNameResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [PropertyFetch::class];
    }

    /**
     * @param PropertyFetch $node
     * @return string[]
     */
    public function process(Node $node, Scope $scope): array
    {
        // union types are supported since PHP 8+ only
        if (PHP_VERSION < 74000 && ! StaticPHPUnitEnvironment::isPHPUnitRun()) {
            return [];
        }

        $propertyName = $this->simpleNameResolver->getName($node->name);
        if ($propertyName === null) {
            return [];
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return [];
        }

        if (! $classReflection->hasNativeProperty($propertyName)) {
            return [];
        }

        $propertyReflection = $classReflection->getNativeProperty($propertyName);

        if (! $propertyReflection->getNativeType() instanceof MixedType) {
            return [];
        }

        if ($propertyReflection->getPhpDocType() instanceof MixedType) {
            return [];
        }

        return [self::ERROR_MESSAGE];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(self::ERROR_MESSAGE, [
            new CodeSample(
                <<<'CODE_SAMPLE'
/**
 * @var string
 */
public $name;
CODE_SAMPLE
            ,
                <<<'CODE_SAMPLE'
public string $name;
CODE_SAMPLE
            ),
        ]);
    }
}
