<?php

declare(strict_types=1);

namespace Brimmar\PHPStan\Rules;

use Brimmar\PhpOption\None;
use Brimmar\PhpOption\Some;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\Cast\Void_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ExtendedMethodReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\NeverType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;

/**
 * @implements Rule<Expression>
 */
class OptionUnusedRule implements Rule
{
    /**
     * @param class-string $optionInterface
     */
    public function __construct(
        private ReflectionProvider $reflectionProvider,
        private string $optionInterface = 'Brimmar\PhpOption\Interfaces\Option',
    ) {}

    public function getNodeType(): string
    {
        return Expression::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $expr = $node->expr;
        if ($expr instanceof Assign || $expr instanceof AssignRef || $expr instanceof AssignOp || $expr instanceof Void_ || $expr instanceof Variable || $expr instanceof PropertyFetch) {
            return [];
        }

        if ($this->discardIsAlreadyReported($expr, $scope)) {
            return [];
        }

        $type = $scope->getType($expr);
        if ((new NeverType())->isSuperTypeOf($type)->yes()) {
            return [];
        }

        if (! (new ObjectType($this->optionInterface))->isSuperTypeOf($type)->yes()) {
            return [];
        }

        return [
            RuleErrorBuilder::message($this->message($type))
                ->identifier('option.unused')
                ->build(),
        ];
    }

    private function message(Type $type): string
    {
        $name = $type->describe(VerbosityLevel::typeOnly());
        $definite = (new ObjectType(Some::class))->isSuperTypeOf($type)->yes()
            || (new ObjectType(None::class))->isSuperTypeOf($type)->yes();

        if ($definite) {
            return "Unused {$name}. This value must be used.";
        }

        return "Unused {$name}. This value may be None, which should be handled.";
    }

    private function discardIsAlreadyReported(Expr $expr, Scope $scope): bool
    {
        if ($expr instanceof MethodCall && $expr->name instanceof Identifier) {
            $method = $scope->getMethodReflection($scope->getType($expr->var), $expr->name->toString());

            return $method instanceof ExtendedMethodReflection && $method->mustUseReturnValue()->yes();
        }

        if ($expr instanceof StaticCall && $expr->name instanceof Identifier && $expr->class instanceof Name) {
            $className = $scope->resolveName($expr->class);
            $class = $this->reflectionProvider->hasClass($className)
                ? $this->reflectionProvider->getClass($className)
                : null;
            if ($class === null || ! $class->hasMethod($expr->name->toString())) {
                return false;
            }

            $method = $class->getMethod($expr->name->toString(), $scope);

            return $method->mustUseReturnValue()->yes();
        }

        if ($expr instanceof FuncCall && $expr->name instanceof Name) {
            if (! $this->reflectionProvider->hasFunction($expr->name, $scope)) {
                return false;
            }

            return $this->reflectionProvider->getFunction($expr->name, $scope)->mustUseReturnValue()->yes();
        }

        return false;
    }
}
