<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariantWithPhpDocs;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Type\ArrayType;
use PHPStan\Type\TypeWithClassName;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Symplify\Astral\Naming\SimpleNameResolver;
use Symplify\PHPStanRules\Rules\AbstractSymplifyRule;
use Symplify\RuleDocGenerator\Contract\ConfigurableRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\PHPStanRules\Tests\Rule\RequireAssertConfigureValueObjectRectorRule\RequireAssertConfigureValueObjectRectorRuleTest
 */
final class RequireAssertConfigureValueObjectRectorRule extends AbstractSymplifyRule implements ConfigurableRuleInterface
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Method configure() with passed value object must contain assert to verify passed type';

    /**
     * @var NodeFinder
     */
    private $nodeFinder;

    /**
     * @var SimpleNameResolver
     */
    private $simpleNameResolver;

    public function __construct(SimpleNameResolver $simpleNameResolver, NodeFinder $nodeFinder)
    {
        $this->nodeFinder = $nodeFinder;
        $this->simpleNameResolver = $simpleNameResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     * @return string[]
     */
    public function process(Node $node, Scope $scope): array
    {
        $classReflection = $scope->getClassReflection();
        if ($classReflection === null) {
            return [];
        }

        if (! $classReflection->isSubclassOf(ConfigurableRectorInterface::class)) {
            return [];
        }

        if (! $this->hasArrayObjectTypeParam($node, $classReflection)) {
            return [];
        }

        if ($this->hasAssertAllIsAOfStaticCall($node)) {
            return [];
        }

        return [self::ERROR_MESSAGE];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(self::ERROR_MESSAGE, [
            new CodeSample(
                <<<'CODE_SAMPLE'
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;

final class SomeRector implements ConfigurableRectorInterface
{
    /**
     * @param array<string, SomeValueObject[]> $configuration
     */
    public function configure(array $configuration): void
    {
        $valueObjects = $configuration[self::SOME_KEY] ?? [];
    }
}
CODE_SAMPLE
            ,
                <<<'CODE_SAMPLE'
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;

final class SomeRector implements ConfigurableRectorInterface
{
    /**
     * @param array<string, SomeValueObject[]> $configuration
     */
    public function configure(array $configuration): void
    {
        $valueObjects = $configuration[self::SOME_KEY] ?? [];
        Assert::allIsAOf($valueObjects, SomeValueObject::class);
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    private function hasAssertAllIsAOfStaticCall(ClassMethod $classMethod): bool
    {

        /** @var StaticCall[] $staticCalls */
        $staticCalls = $this->nodeFinder->findInstanceOf($classMethod, StaticCall::class);

        foreach ($staticCalls as $staticCall) {
            if (! $this->simpleNameResolver->isName($staticCall->class, Assert::class)) {
                continue;
            }

            if ($this->simpleNameResolver->isName($staticCall->name, 'allIsAOf')) {
                return true;
            }
        }

        return false;
    }

    private function hasArrayObjectTypeParam(ClassMethod $classMethod, ClassReflection $classReflection): bool
    {
        /** @var string $methodName */
        $methodName = $this->simpleNameResolver->getName($classMethod);
        if (! $classReflection->hasMethod($methodName)) {
            return false;
        }

        $classMethodReflection = $classReflection->getNativeMethod($methodName);
        if (! $classMethodReflection instanceof PhpMethodReflection) {
            return false;
        }

        foreach ($classMethodReflection->getVariants() as $parametersAcceptorWithPhpDocs) {
            if (! $parametersAcceptorWithPhpDocs instanceof FunctionVariantWithPhpDocs) {
                continue;
            }

            if ($parametersAcceptorWithPhpDocs->getParameters() === []) {
                continue;
            }

            $configurationParameterReflection = $parametersAcceptorWithPhpDocs->getParameters()[0];
            $phpDocType = $configurationParameterReflection->getPhpDocType();
            if (! $phpDocType instanceof ArrayType) {
                continue;
            }

            $itemArrayType = $phpDocType->getItemType();
            if (! $itemArrayType instanceof ArrayType) {
                continue;
            }

            if (! $itemArrayType->getItemType() instanceof TypeWithClassName) {
                continue;
            }

            return true;
        }

        return false;
    }
}
