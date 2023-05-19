<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Rule;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ObjectType;
use Rector\PHPStanRules\NodeAnalyzer\SymfonyConfigMethodCallAnalyzer;
use Rector\PHPStanRules\NodeAnalyzer\SymfonyConfigRectorValueObjectResolver;

/**
 * @see \Rector\PHPStanRules\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule\RectorServiceAndValueObjectHaveSameStartsRuleTest
 *
 * @implements Rule<MethodCall>
 */
final class RectorServiceAndValueObjectHaveSameStartsRule implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Value object "%s" should be named "%s" instead to respect used service';

    public function __construct(
        private readonly SymfonyConfigRectorValueObjectResolver $symfonyConfigRectorValueObjectResolver,
        private readonly SymfonyConfigMethodCallAnalyzer $symfonyConfigMethodCallAnalyzer,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $this->symfonyConfigMethodCallAnalyzer->isRuleWithConfiguration($node, $scope)) {
            return [];
        }

        $shortClass = $this->resolveSetMethodCallShortClass($node, $scope);
        if ($shortClass === null) {
            return [];
        }

        $valueObjectShortClass = $this->resolveValueObjectShortClass($node);
        if ($valueObjectShortClass === null) {
            return [];
        }

        if (! str_ends_with($shortClass, 'Rector')) {
            return [];
        }

        $expectedValueObjectShortClass = Strings::substring($shortClass, 0, -Strings::length('Rector'));
        if ($expectedValueObjectShortClass === $valueObjectShortClass) {
            return [];
        }

        $errorMessage = sprintf(self::ERROR_MESSAGE, $valueObjectShortClass, $expectedValueObjectShortClass);
        return [$errorMessage];
    }

    private function resolveSetMethodCallShortClass(MethodCall $methodCall, Scope $scope): ?string
    {
        $firstArg = $methodCall->getArgs()[0];
        $firstArgValueType = $scope->getType($firstArg->value);

        if (! $firstArgValueType instanceof ConstantStringType) {
            return null;
        }

        $stringValue = $firstArgValueType->getValue();
        return Strings::after($stringValue, '\\', -1);
    }

    private function resolveValueObjectShortClass(MethodCall $methodCall): ?string
    {
        $valueObjectType = $this->symfonyConfigRectorValueObjectResolver->resolveFromRuleWithConfigurationMethodCall($methodCall);
        if (! $valueObjectType instanceof ObjectType) {
            return null;
        }

        // is it implements interface, it can have many forms
        if (! $this->reflectionProvider->hasClass($valueObjectType->getClassName())) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($valueObjectType->getClassName());
        if ($classReflection->getInterfaces() !== []) {
            return null;
        }

        return Strings::after($valueObjectType->getClassName(), '\\', -1);
    }
}
