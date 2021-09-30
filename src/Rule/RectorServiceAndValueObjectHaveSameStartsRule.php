<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Rule;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use Rector\PHPStanRules\NodeAnalyzer\SymfonyConfigRectorValueObjectResolver;
use Symplify\Astral\Naming\SimpleNameResolver;
use Symplify\PHPStanRules\Rules\AbstractSymplifyRule;
use Symplify\PHPStanRules\Symfony\NodeAnalyzer\SymfonyConfigMethodCallAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\PHPStanRules\Tests\Rule\RectorServiceAndValueObjectHaveSameStartsRule\RectorServiceAndValueObjectHaveSameStartsRuleTest
 */
final class RectorServiceAndValueObjectHaveSameStartsRule extends AbstractSymplifyRule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Value object "%s" should be named "%s" instead to respect used service';

    public function __construct(
        private SimpleNameResolver $simpleNameResolver,
        private SymfonyConfigRectorValueObjectResolver $symfonyConfigRectorValueObjectResolver,
        private SymfonyConfigMethodCallAnalyzer $symfonyConfigMethodCallAnalyzer,
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     * @return string[]
     */
    public function process(Node $node, Scope $scope): array
    {
        if (! $this->symfonyConfigMethodCallAnalyzer->isServicesSet($node, $scope)) {
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
                new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(
                    <<<'CODE_SAMPLE'
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(SomeRector::class)
        ->call('configure', [[
            new Another()
        ]]);
};
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(SomeRector::class)
        ->call('configure', [[
            new Some()
        ]]);
};
CODE_SAMPLE
                ),
            ]
        );
    }

    private function resolveSetMethodCallShortClass(MethodCall $methodCall): ?string
    {
        $firstArgOrVariablePlaceholder = $methodCall->args[0];
        if (! $firstArgOrVariablePlaceholder instanceof Arg) {
            return null;
        }

        $setFirstArgValue = $firstArgOrVariablePlaceholder->value;
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
        $valueObjectClass = $this->symfonyConfigRectorValueObjectResolver->resolveFromSetMethodCall($methodCall);
        if ($valueObjectClass === null) {
            return null;
        }

        // is it implements interface, it can have many forms
        $interfaces = class_implements($valueObjectClass);
        if ($interfaces !== []) {
            return null;
        }

        return $this->simpleNameResolver->resolveShortName($valueObjectClass);
    }
}