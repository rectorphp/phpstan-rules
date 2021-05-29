<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use Symplify\Astral\Naming\SimpleNameResolver;
use Symplify\Astral\NodeValue\NodeValueResolver;
use Symplify\PHPStanRules\Rules\AbstractSymplifyRule;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\PHPStanRules\Tests\Rule\CallConfigureWithArrayRule\CallConfigureWithArrayRuleTest
 */
final class CallConfigureWithArrayRule extends AbstractSymplifyRule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Service call("configure") must be followed by exact array';

    public function __construct(
        private SimpleNameResolver $simpleNameResolver,
        private NodeValueResolver $nodeValueResolver
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
        if (! $this->simpleNameResolver->isName($node->name, 'call')) {
            return [];
        }

        if (count($node->args) !== 2) {
            return [];
        }

        $firstValue = $this->nodeValueResolver->resolve($node->args[0]->value, $scope->getFile());
        if ($firstValue !== 'configure') {
            return [];
        }

        $secondArgValue = $node->args[1]->value;
        if ($this->isTwiceNestedArray($secondArgValue)) {
            return [];
        }

        return [self::ERROR_MESSAGE];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(self::ERROR_MESSAGE, [
            new CodeSample(
                <<<'CODE_SAMPLE'
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SomeClass::class)
        ->call('configure', [
            'value'
        ]);
};
CODE_SAMPLE
            ,
                <<<'CODE_SAMPLE'
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SomeClass::class)
        ->call('configure', [[
            'value'
        ]]);
};
CODE_SAMPLE
            ),
        ]);
    }

    private function isTwiceNestedArray(Expr $expr): bool
    {
        if (! $expr instanceof Array_) {
            return false;
        }

        if (count($expr->items) !== 1) {
            return false;
        }

        $firstItem = $expr->items[0];
        if (! $firstItem instanceof ArrayItem) {
            return false;
        }

        if (! $firstItem->value instanceof Array_) {
            return false;
        }

        if (count($firstItem->value->items) !== 1) {
            return false;
        }

        $firstNestedItem = $firstItem->value->items[0];
        if (! $firstNestedItem instanceof ArrayItem) {
            return false;
        }

        return ! $firstNestedItem->value instanceof Array_;
    }
}
