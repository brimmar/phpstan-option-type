<?php

declare(strict_types=1);

namespace Brimmar\PHPStan\Rules;

use Brimmar\PhpOption\Some;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;

/**
 * @implements Rule<MethodCall>
 */
class OptionUnsafeMethodCallRule implements Rule
{
    public function __construct(private string $optionInterface = 'Brimmar\PhpOption\Interfaces\Option') {}

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (! $node->name instanceof Identifier) {
            return [];
        }

        if (! in_array($node->name->name, ['unwrap', 'expect'], true)) {
            return [];
        }

        $type = $scope->getType($node->var);
        $option = new ObjectType($this->optionInterface);
        if (! $option->isSuperTypeOf($type)->yes()) {
            return [];
        }

        if ((new ObjectType(Some::class))->isSuperTypeOf($type)->yes()) {
            return [];
        }

        return [
            RuleErrorBuilder::message("Potentially unsafe use of {$node->name->name}() on Option type without proper checks. Consider using isSome() checks, match(), or unwrapOr() for safer handling.")
                ->identifier('option.unsafeMethod')
                ->build(),
        ];
    }
}
