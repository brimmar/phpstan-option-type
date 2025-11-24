<?php

declare(strict_types=1);

namespace Brimmar\PHPStan\Visitors;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ParentConnectingVisitor extends NodeVisitorAbstract
{
    /** @var Node[] */
    private array $stack = [];

    public function beforeTraverse(array $nodes): ?array
    {
        $this->stack = [];
        return null;
    }

    public function enterNode(Node $node): ?Node
    {
        if (!empty($this->stack)) {
            $node->setAttribute('parent', $this->stack[count($this->stack) - 1]);
        }
        $this->stack[] = $node;
        return null;
    }

    public function leaveNode(Node $node): ?Node
    {
        array_pop($this->stack);
        return null;
    }
}
