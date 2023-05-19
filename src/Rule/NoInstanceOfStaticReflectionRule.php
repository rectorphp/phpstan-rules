<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\PHPStanRules\TypeAnalyzer\AllowedAutoloadedTypeAnalyzer;
use Symplify\PHPStanRules\Rules\AbstractSymplifyRule;

/**
 * @see https://github.com/rectorphp/rector/issues/5906
 *
 * @see \Rector\PHPStanRules\Tests\Rule\NoInstanceOfStaticReflectionRule\NoInstanceOfStaticReflectionRuleTest
 */
final class NoInstanceOfStaticReflectionRule extends AbstractSymplifyRule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Instead of "instanceof/is_a()" use ReflectionProvider service or "(new ObjectType(<desired_type>))->isSuperTypeOf(<element_type>)" for static reflection to work';

    public function __construct(
        private readonly AllowedAutoloadedTypeAnalyzer $allowedAutoloadedTypeAnalyzer
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Instanceof_::class, FuncCall::class];
    }

    /**
     * @param Instanceof_|FuncCall $node
     * @return string[]
     */
    public function process(Node $node, Scope $scope): array
    {
        $exprStaticType = $this->resolveExprStaticType($node, $scope);
        if (! $exprStaticType instanceof Type) {
            return [];
        }

        if ($this->allowedAutoloadedTypeAnalyzer->isAllowedType($exprStaticType)) {
            return [];
        }

        return [self::ERROR_MESSAGE];
    }

    private function resolveExprStaticType(FuncCall|Instanceof_ $node, Scope $scope): ?Type
    {
        if ($node instanceof Instanceof_) {
            return $this->resolveInstanceOfType($node, $scope);
        }

        if (! $node->name instanceof Name) {
            return null;
        }

        if ($node->name->toString() !== 'is_a') {
            return null;
        }

        $typeArgValue = $node->getArgs()[1]->value;
        return $scope->getType($typeArgValue);
    }

    private function resolveInstanceOfType(Instanceof_ $instanceof, Scope $scope): Type
    {
        if ($instanceof->class instanceof Name) {
            $className = $instanceof->class->toString();
            if ($className === 'self') {
                $classReflection = $scope->getClassReflection();
                if ($classReflection instanceof ClassReflection) {
                    return new ObjectType($classReflection->getName(), null, $classReflection);
                }
            }

            return new ConstantStringType($instanceof->class->toString());
        }

        return $scope->getType($instanceof->class);
    }
}
