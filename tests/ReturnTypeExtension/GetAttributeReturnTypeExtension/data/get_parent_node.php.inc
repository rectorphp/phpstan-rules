<?php

declare(strict_types=1);

use PhpParser\Node\Scalar\String_;
use Rector\NodeTypeResolver\Node\AttributeKey;

$node = new String_('value');

$parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
\PHPStan\Testing\assertType('PhpParser\Node|null', $parentNode);

$scope = $node->getAttribute(AttributeKey::SCOPE);
\PHPStan\Testing\assertType('PHPStan\Analyser\Scope', $scope);
