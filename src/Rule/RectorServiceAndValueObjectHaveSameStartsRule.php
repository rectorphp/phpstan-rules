<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Rule;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Type\ObjectType;
use Rector\PHPStanRules\NodeAnalyzer\SymfonyConfigMethodCallAnalyzer;
use Rector\PHPStanRules\NodeAnalyzer\SymfonyConfigRectorValueObjectResolver;
use Symplify\Astral\Naming\SimpleNameResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

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
        private SimpleNameResolver $simpleNameResolver,
        private SymfonyConfigRectorValueObjectResolver $symfonyConfigRectorValueObjectResolver,
        private SymfonyConfigMethodCallAnalyzer $symfonyConfigMethodCallAnalyzer,
        private ReflectionProvider $reflectionProvider,
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

        $shortClass = $this->resolveSetMethodCallShortClass($node);
        if ($shortClass === null) {
            return [];
        }

        $valueObjectShortClass = $this->resolveValueObjectShortClass($node);
        if ($valueObjectShortClass === null) {
            return [];
        }

        if (! \str_ends_with($shortClass, 'Rector')) {
            return [];
        }

        $expectedValueObjectShortClass = Strings::substring($shortClass, 0, -Strings::length('Rector'));
        if ($expectedValueObjectShortClass === $valueObjectShortClass) {
            return [];
        }

        $errorMessage = sprintf(self::ERROR_MESSAGE, $valueObjectShortClass, $expectedValueObjectShortClass);
        return [$errorMessage];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Make specific service suffix to use similar value object names for configuring in Symfony configs',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(SomeRector::class, [
        new Another()
    ]);
};
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(SomeRector::class, [
        new Some()
    ]);
};
CODE_SAMPLE
                ),
            ]
        );
    }

    private function resolveSetMethodCallShortClass(MethodCall $methodCall): ?string
    {
        $firstArg = $methodCall->getArgs()[0];

        $setFirstArgValue = $firstArg->value;
        if (! $setFirstArgValue instanceof ClassConstFetch) {
            return null;
        }

        $rectorClass = $this->simpleNameResolver->getName($setFirstArgValue->class);
        if ($rectorClass === null) {
            return null;
        }

        return $this->simpleNameResolver->resolveShortName($rectorClass);
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

        return $this->simpleNameResolver->resolveShortName($valueObjectType->getClassName());
    }
}
