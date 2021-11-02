<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\ReturnTypeExtension;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Use_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\ArrayType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\IntegerType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Symplify\Astral\NodeValue\NodeValueResolver;
use Symplify\SmartFileSystem\SmartFileInfo;

final class GetAttributeReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    /**
     * @var array<string, string|string[]>>
     */
    private const ARGUMENT_KEY_TO_RETURN_TYPE = [
        'Rector\NodeTypeResolver\Node\AttributeKey::FILE_INFO' => SmartFileInfo::class,
        'Rector\NodeTypeResolver\Node\AttributeKey::RESOLVED_NAME' => Name::class,
        'Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE' => ClassLike::class,
        'Rector\NodeTypeResolver\Node\AttributeKey::METHOD_NODE' => ClassMethod::class,
        'Rector\NodeTypeResolver\Node\AttributeKey::CURRENT_EXPRESSION' => Stmt::class,
        'Rector\NodeTypeResolver\Node\AttributeKey::PREVIOUS_STATEMENT' => Stmt::class,
        'Rector\NodeTypeResolver\Node\AttributeKey::SCOPE' => Scope::class,
        # Node
        'Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NODE' => Node::class,
        'Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE' => Node::class,
        'Rector\NodeTypeResolver\Node\AttributeKey::NEXT_NODE' => Node::class,
        'Rector\NodeTypeResolver\Node\AttributeKey::PREVIOUS_NODE' => Node::class,
        'Rector\NodeTypeResolver\Node\AttributeKey::USE_NODES' => [Use_::class],
        # scalars
        'Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NAME' => 'string',
    ];

    public function __construct(
        private NodeValueResolver $nodeValueResolver
    ) {
    }

    public function getClass(): string
    {
        return Node::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'getAttribute';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): Type {
        $returnType = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants())->getReturnType();

        $argumentValue = $this->resolveArgumentValue($methodCall->args[0]->value, $scope);
        if ($argumentValue === null) {
            return $returnType;
        }

        if (! isset(self::ARGUMENT_KEY_TO_RETURN_TYPE[$argumentValue])) {
            return $returnType;
        }

        $knownReturnType = self::ARGUMENT_KEY_TO_RETURN_TYPE[$argumentValue];
        if ($knownReturnType === 'string') {
            return new UnionType([new StringType(), new NullType()]);
        }

        if (is_array($knownReturnType) && count($knownReturnType) === 1) {
            $arrayType = new ArrayType(new IntegerType(), new ObjectType($knownReturnType[0]));
            return new UnionType([$arrayType, new NullType()]);
        }

        if (is_string($knownReturnType)) {
            return new UnionType([new ObjectType($knownReturnType), new NullType()]);
        }

        return $returnType;
    }

    private function resolveArgumentValue(Expr $expr, Scope $scope): ?string
    {
        if ($expr instanceof ClassConstFetch) {
            $resolvedValue = $this->nodeValueResolver->resolve($expr, $scope->getFile());
            if (! is_string($resolvedValue)) {
                return null;
            }

            return $resolvedValue;
        }

        return null;
    }
}
