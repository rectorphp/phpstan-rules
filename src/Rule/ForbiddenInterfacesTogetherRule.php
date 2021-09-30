<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Rule;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Reflection\ClassReflection;
use Symplify\PHPStanRules\Rules\AbstractSymplifyRule;
use Symplify\RuleDocGenerator\Contract\ConfigurableRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\PHPStanRules\Tests\Rule\ForbiddenInterfacesTogetherRule\ForbiddenInterfacesTogetherRuleTest
 */
final class ForbiddenInterfacesTogetherRule extends AbstractSymplifyRule implements ConfigurableRuleInterface
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Interfaces "%s" cannot be use together. Extract them to 2 separated classes';

    /**
     * @param array<string[]> $forbiddenInterfaceGroups
     */
    public function __construct(
        private array $forbiddenInterfaceGroups = []
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [InClassNode::class];
    }

    /**
     * @param InClassNode $node
     * @return string[]
     */
    public function process(Node $node, Scope $scope): array
    {
        $classReflection = $scope->getClassReflection();
        if ($classReflection === null) {
            return [];
        }

        if (! $classReflection->isClass()) {
            return [];
        }

        $interfaceNames = $this->resolveInterfaceNames($classReflection);

        foreach ($this->forbiddenInterfaceGroups as $forbiddenInterfaceGroup) {
            $matchingInterfaces = array_intersect($forbiddenInterfaceGroup, $interfaceNames);
            if ($matchingInterfaces !== $forbiddenInterfaceGroup) {
                continue;
            }

            $errorMessage = sprintf(self::ERROR_MESSAGE, implode('", "', $forbiddenInterfaceGroup));
            return [$errorMessage];
        }

        return [];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(self::ERROR_MESSAGE, [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
final class UltimateService implements SubscriberInterface, DispatcherInterface
{
} 
CODE_SAMPLE
            ,
                <<<'CODE_SAMPLE'
final class Subscriber implements SubscriberInterface
{
}

final class Dispatcher implements DispatcherInterface
{
}
CODE_SAMPLE
            ,
                [
                    'forbiddenInterfaceGroups' => [
                        ['SubscriberInterface', 'DispatcherInterface'],
                    ],
                ]
            ),
        ]);
    }

    /**
     * @return string[]
     */
    private function resolveInterfaceNames(ClassReflection $classReflection): array
    {
        $interfaceNames = [];
        foreach ($classReflection->getInterfaces() as $interfaceReflection) {
            $interfaceNames[] = $interfaceReflection->getName();
        }

        return $interfaceNames;
    }
}
