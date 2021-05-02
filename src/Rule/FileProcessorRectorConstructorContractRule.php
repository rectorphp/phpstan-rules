<?php

declare(strict_types=1);

namespace Rector\RectorPHPStanRules\Rule;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariantWithPhpDocs;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Type\ArrayType;
use PHPStan\Type\ObjectType;
use Symplify\PackageBuilder\ValueObject\MethodName;
use Symplify\PHPStanRules\Rules\AbstractSymplifyRule;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\RectorPHPStanRules\Tests\Rule\FileProcessorRectorConstructorContractRule\FileProcessorRectorConstructorContractRuleTest
 */
final class FileProcessorRectorConstructorContractRule extends AbstractSymplifyRule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'File processor must require Rector rules in constructor via TypeRectorInterface[] $typeRectors array autowire';

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
        if (! $this->isFileProcessorClass($scope)) {
            return [];
        }

        /** @var ClassReflection $classReflection */
        $classReflection = $scope->getClassReflection();

        if (! $classReflection->hasMethod(MethodName::CONSTRUCTOR)) {
            return [self::ERROR_MESSAGE];
        }

        $classMethodReflection = $classReflection->getConstructor();

        $firstVariant = $classMethodReflection->getVariants()[0];
        if ($firstVariant instanceof FunctionVariantWithPhpDocs) {
            foreach ($firstVariant->getParameters() as $parameterReflection) {
                if ($this->isRectorArrayParameter($parameterReflection)) {
                    return [];
                }
            }
        }

        return [self::ERROR_MESSAGE];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(self::ERROR_MESSAGE, [
            new CodeSample(
                <<<'CODE_SAMPLE'
use Rector\Core\Contract\Processor\FileProcessorInterface;

final class SomeFileProcessor implements FileProcessorInterface
{
    public function run()
    {
    }
} 
CODE_SAMPLE
            ,
                <<<'CODE_SAMPLE'
use Rector\Core\Contract\Processor\FileProcessorInterface;

final class SomeFileProcessor implements FileProcessorInterface
{
    /**
     * @var SomeRectorInterface[]
     */
    private $someRectors = [];
    
    /**
     * @param SomeRectorInterface[] $someRectors
     */
    public function __construct(array $someRectors)
    {
        $this->someRectors = $someRectors;
    }
    
    public function run()
    {
    }
} 
CODE_SAMPLE
            ),
        ]);
    }

    private function isFileProcessorClass(Scope $scope): bool
    {
        $classReflection = $scope->getClassReflection();
        if ($classReflection === null) {
            return false;
        }

        return $classReflection->implementsInterface('Rector\Core\Contract\Processor\FileProcessorInterface');
    }

    private function isRectorArrayParameter(ParameterReflection $parameterReflection): bool
    {
        $parameterType = $parameterReflection->getType();
        if (! $parameterType instanceof ArrayType) {
            return false;
        }

        if (! $parameterType->getItemType() instanceof ObjectType) {
            return false;
        }

        $objectType = $parameterType->getItemType();
        $rectorObjectType = new ObjectType('Rector\Core\Contract\Rector\RectorInterface');

        return $rectorObjectType->isSuperTypeOf($objectType)->yes();
    }
}
