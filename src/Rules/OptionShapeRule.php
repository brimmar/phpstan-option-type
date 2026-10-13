<?php

declare(strict_types=1);

namespace Brimmar\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;

/**
 * @implements Rule<MethodCall>
 */
class OptionShapeRule implements Rule
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

        $method = $node->name->name;
        if (! in_array($method, ['flatten', 'transpose', 'unzip'], true)) {
            return [];
        }

        $type = $scope->getType($node->var);
        if (! $type instanceof GenericObjectType) {
            return [];
        }

        if (! (new ObjectType($this->optionInterface))->isSuperTypeOf($type)->yes()) {
            return [];
        }

        $inner = $type->getTypes()[0] ?? null;
        if (! $inner instanceof Type) {
            return [];
        }

        if ($method === 'unzip') {
            if (! $inner->isArray()->no()) {
                return [];
            }

            return [
                RuleErrorBuilder::message('Option::unzip() is for an array. The contained type is '.$inner->describe(VerbosityLevel::typeOnly()).'.')
                    ->identifier('option.unzip')
                    ->build(),
            ];
        }

        $expected = $method === 'flatten' ? $this->optionInterface : 'Brimmar\PhpResult\Interfaces\Result';
        if (! (new ObjectType($expected))->isSuperTypeOf($inner)->no()) {
            return [];
        }

        $needed = $method === 'flatten' ? 'Option<Option<T>>' : 'Option<Result<T, E>>';

        return [
            RuleErrorBuilder::message("Option::{$method}() is for {$needed}. The contained type is ".$inner->describe(VerbosityLevel::typeOnly()).'.')
                ->identifier('option.'.$method)
                ->build(),
        ];
    }
}
