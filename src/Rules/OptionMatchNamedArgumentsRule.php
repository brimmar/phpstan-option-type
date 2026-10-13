<?php

declare(strict_types=1);

namespace Brimmar\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;

/**
 * @implements Rule<MethodCall>
 */
class OptionMatchNamedArgumentsRule implements Rule
{
    public function __construct(private string $optionInterface = 'Brimmar\PhpOption\Interfaces\Option') {}

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (! $node->name instanceof Node\Identifier) {
            return [];
        }

        if ($node->name->name !== 'match') {
            return [];
        }

        $type = $scope->getType($node->var);

        $optionType = new ObjectType($this->optionInterface);
        if (! $optionType->isSuperTypeOf($type)->yes()) {
            return [];
        }

        if (count($node->getArgs()) !== 2) {
            return [
                RuleErrorBuilder::message('Option::match() must have exactly two arguments.')
                    ->identifier('option.matchArgs')
                    ->build(),
            ];
        }

        $names = [];
        foreach ($node->getArgs() as $arg) {
            if ($arg->name === null) {
                return [
                    RuleErrorBuilder::message('Option::match() must use named arguments "Some" and "None".')
                        ->identifier('option.matchNamedArgs')
                        ->build(),
                ];
            }

            $names[] = $arg->name->name;
        }

        sort($names);
        if ($names !== ['None', 'Some']) {
            return [
                RuleErrorBuilder::message('Option::match() must use named arguments "Some" and "None".')
                    ->identifier('option.matchNamedArgs')
                    ->build(),
            ];
        }

        return [];
    }
}
