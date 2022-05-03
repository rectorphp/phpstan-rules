<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Name\Relative;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Type\Constant\ConstantStringType;
use Symplify\Astral\Naming\SimpleNameResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\PHPStanRules\Tests\Rule\NoLeadingBackslashInNameRule\NoLeadingBackslashInNameRuleTest
 *
 * @implements Rule<New_>
 */
final class NoLeadingBackslashInNameRule implements Rule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Instead of "new Name(\'\\\\Foo\')" use "new FullyQualified(\'Foo\')"';

    public function __construct(
        private SimpleNameResolver $simpleNameResolver
    ) {
    }

    public function getNodeType(): string
    {
        return New_::class;
    }

    /**
     * @param New_ $node
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->args === []) {
            return [];
        }

        $className = $this->simpleNameResolver->getName($node->class);
        if (! in_array($className, [Name::class, FullyQualified::class, Relative::class], true)) {
            return [];
        }

        $argValue = $node->args[0]->value;
        $argType = $scope->getType($argValue);

        if (! $argType instanceof ConstantStringType) {
            return [];
        }

        if (! str_starts_with($argType->getValue(), '\\')) {
            return [];
        }

        return [self::ERROR_MESSAGE];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(self::ERROR_MESSAGE, [
            new CodeSample(
                <<<'CODE_SAMPLE'
new Name('\\Closure');
CODE_SAMPLE
            ,
                <<<'CODE_SAMPLE'
new FullyQualified('Closure');
CODE_SAMPLE
            ),
        ]);
    }
}
