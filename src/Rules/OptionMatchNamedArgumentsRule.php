<?php

declare(strict_types=1);

namespace Brimmar\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Type\ObjectType;

/**
 * @implements Rule<MethodCall>
 */
class OptionMatchNamedArgumentsRule implements Rule
{
    private string $optionInterface;

    public function __construct(string $optionInterface = 'Brimmar\PhpOption\Interfaces\Option')
    {
        $this->optionInterface = $optionInterface;
    }

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (! $node instanceof MethodCall) {
            return [];
        }

        if (! $node->name instanceof Node\Identifier) {
            return [];
        }

        if ($node->name->name !== 'match') {
            return [];
        }

        $type = $scope->getType($node->var);
        if (! $type instanceof ObjectType) {
            return [];
        }

        if (! $type->isInstanceOf($this->optionInterface)->yes()) {
            return [];
        }

        if (count($node->getArgs()) !== 2) {
            return ['Option::match() must have exactly two arguments.'];
        }

        $okArg = $node->getArgs()[0];
        $errArg = $node->getArgs()[1];

        if ($okArg->name === null || $errArg->name === null) {
            return ['Option::match() must use named arguments "Some" and "None".'];
        }

        if ($okArg->name->name !== 'Ok' || $errArg->name->name !== 'Err') {
            return ['Option::match() must use named arguments "Some" and "None" in this order.'];
        }

        return [];
    }
}
