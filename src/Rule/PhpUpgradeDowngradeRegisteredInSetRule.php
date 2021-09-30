<?php

declare(strict_types=1);

namespace Rector\PHPStanRules\Rule;

use Nette\Utils\Strings;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Set\ValueObject\DowngradeSetList;
use Rector\Set\ValueObject\SetList;
use Symplify\PHPStanRules\Rules\AbstractSymplifyRule;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\FileSystemGuard;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

/**
 * @see \Rector\PHPStanRules\Tests\Rule\PhpUpgradeDowngradeRegisteredInSetRule\PhpUpgradeDowngradeRegisteredInSetRuleTest
 */
final class PhpUpgradeDowngradeRegisteredInSetRule extends AbstractSymplifyRule
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Register "%s" service to "%s" config set';

    /**
     * @var string
     * @see https://regex101.com/r/VGmFKR/1
     */
    private const DOWNGRADE_PREFIX_REGEX = '#(?<is_downgrade>Downgrade)?Php(?<version>\d+)#';

    private FileSystemGuard $fileSystemGuard;

    public function __construct(
        private SmartFileSystem $smartFileSystem,
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
        $className = $this->matchRectorClassName($scope);
        if ($className === null) {
            return [];
        }

        $configFilePath = $this->resolveRelatedConfigFilePath($className);
        if ($configFilePath === null) {
            return [];
        }

        $configContent = $this->smartFileSystem->readFile($configFilePath);

        // is rule registered?
        if (str_contains($configContent, $className)) {
            return [];
        }

        $errorMessage = $this->createErrorMessage($configFilePath, $className);
        return [$errorMessage];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(self::ERROR_MESSAGE, [
            new CodeSample(
                <<<'CODE_SAMPLE'
// config/set/php74.php
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
// config/set/php74.php
$services->set(RealToFloatTypeCastRector::class);
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @param class-string<RectorInterface> $className
     */
    private function resolveRelatedConfigFilePath(string $className): string|null
    {
        $match = Strings::match($className, self::DOWNGRADE_PREFIX_REGEX);
        if ($match === null) {
            return null;
        }

        $constantName = 'PHP_' . $match['version'];
        if ($match['is_downgrade']) {
            return constant(DowngradeSetList::class . '::' . $constantName);
        }

        return constant(SetList::class . '::' . $constantName);
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    private function createErrorMessage(string $configFilePath, string $rectorClass): string
    {
        $configFileInfo = new SmartFileInfo($configFilePath);
        $configFilename = $configFileInfo->getFilename();

        return sprintf(self::ERROR_MESSAGE, $rectorClass, $configFilename);
    }

    /**
     * @return class-string<RectorInterface>|null
     */
    private function matchRectorClassName(Scope $scope): string|null
    {
        $classReflection = $scope->getClassReflection();
        if ($classReflection === null) {
            return null;
        }

        if (! $classReflection->implementsInterface(RectorInterface::class)) {
            return null;
        }

        // configurable Rector can be registered optionally
        if ($classReflection->implementsInterface(ConfigurableRectorInterface::class)) {
            return null;
        }

        return $classReflection->getName();
    }
}
