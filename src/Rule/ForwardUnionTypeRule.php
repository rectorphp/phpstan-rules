<?php

declare(strict_types=1);

namespace Rector\RectorPHPStanRules\Rule;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Node\InFunctionNode;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\FunctionVariantWithPhpDocs;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\UnionType;
use Rector\RectorPHPStanRules\TypeAnalyzer\InlineableTypeAnalyzer;
use Symplify\EasyTesting\PHPUnit\StaticPHPUnitEnvironment;
use Symplify\PHPStanRules\Rules\AbstractSymplifyRule;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\RectorPHPStanRules\Tests\Rule\ForwardUnionTypeRule\ForwardUnionTypeRuleTest
 */
final class ForwardUnionTypeRule extends AbstractSymplifyRule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'This union type might be inlined to PHP. Do you have confidence it is correct? Put it here';

    /**
     * @var InlineableTypeAnalyzer
     */
    private $inlineableTypeAnalyzer;

    public function __construct(InlineableTypeAnalyzer $inlineableTypeAnalyzer)
    {
        $this->inlineableTypeAnalyzer = $inlineableTypeAnalyzer;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [InClassMethodNode::class, InFunctionNode::class];
    }

    /**
     * @param InClassMethodNode|InFunctionNode $node
     * @return string[]
     */
    public function process(Node $node, Scope $scope): array
    {
        // union types are supported since PHP 8+ only
        if (PHP_VERSION < 80000 && ! StaticPHPUnitEnvironment::isPHPUnitRun()) {
            return [];
        }

        $classMethodReflection = $scope->getFunction();
        if (! $classMethodReflection instanceof MethodReflection && ! $classMethodReflection instanceof FunctionReflection) {
            return [];
        }

        $firstVariant = $classMethodReflection->getVariants()[0];
        if ($firstVariant instanceof FunctionVariantWithPhpDocs) {
            if ($this->hasUnionableParamType($firstVariant)) {
                return [self::ERROR_MESSAGE];
            }

            if ($this->hasUnionableReturnType($firstVariant)) {
                return [self::ERROR_MESSAGE];
            }
        }

        return [];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(self::ERROR_MESSAGE, [
            new CodeSample(
                <<<'CODE_SAMPLE'
/**
 * @param int|string $value
 */
function some($value)
{
}
CODE_SAMPLE
            ,
                <<<'CODE_SAMPLE'
function some(int|string $value)
{
}
CODE_SAMPLE
            ),
        ]);
    }

    private function hasUnionableParamType(FunctionVariantWithPhpDocs $functionVariantWithPhpDocs): bool
    {
        foreach ($functionVariantWithPhpDocs->getParameters() as $parameterReflection) {
            $paramPhpDocType = $parameterReflection->getPhpDocType();
            if (! $paramPhpDocType instanceof UnionType) {
                continue;
            }

            // already in native
            if ($parameterReflection->getNativeType() instanceof UnionType) {
                continue;
            }

            if (! $this->inlineableTypeAnalyzer->isInlinableUnionType($paramPhpDocType)) {
                continue;
            }

            return true;
        }

        return false;
    }

    private function hasUnionableReturnType(FunctionVariantWithPhpDocs $functionVariantWithPhpDocs): bool
    {
        $returnPhpDocType = $functionVariantWithPhpDocs->getPhpDocReturnType();
        if (! $returnPhpDocType instanceof UnionType) {
            return false;
        }

        if ($functionVariantWithPhpDocs->getNativeReturnType() instanceof UnionType) {
            return false;
        }

        return $this->inlineableTypeAnalyzer->isInlinableUnionType($returnPhpDocType);
    }
}
