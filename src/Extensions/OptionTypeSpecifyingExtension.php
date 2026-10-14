<?php

declare(strict_types=1);

namespace Brimmar\PHPStan\Extensions;

use Brimmar\PhpOption\None;
use Brimmar\PhpOption\Some;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Analyser\TypeSpecifierAwareExtension;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ErrorType;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\MethodTypeSpecifyingExtension;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;

class OptionTypeSpecifyingExtension implements MethodTypeSpecifyingExtension, TypeSpecifierAwareExtension
{
    private TypeSpecifier $typeSpecifier;

    /**
     * @param class-string $optionInterface
     */
    public function __construct(private string $optionInterface = 'Brimmar\PhpOption\Interfaces\Option') {}

    public function setTypeSpecifier(TypeSpecifier $typeSpecifier): void
    {
        $this->typeSpecifier = $typeSpecifier;
    }

    public function getClass(): string
    {
        return $this->optionInterface;
    }

    public function isMethodSupported(
        MethodReflection $methodReflection,
        MethodCall $node,
        TypeSpecifierContext $context,
    ): bool {
        return in_array($methodReflection->getName(), ['isSome', 'isNone'], true)
            && ($context->true() || $context->false());
    }

    public function specifyTypes(
        MethodReflection $methodReflection,
        MethodCall $node,
        Scope $scope,
        TypeSpecifierContext $context,
    ): SpecifiedTypes {
        $isSome = $methodReflection->getName() === 'isSome';
        $wantsSome = $context->true() ? $isSome : ! $isSome;
        $class = $wantsSome ? Some::class : None::class;

        return $this->typeSpecifier->create(
            $node->var,
            new GenericObjectType($class, [$this->typeParameter($scope->getType($node->var))]),
            TypeSpecifierContext::createTrue(),
            $scope,
        );
    }

    private function typeParameter(Type $type): Type
    {
        $inner = $type->getTemplateType($this->optionInterface, 'T');
        if ($inner instanceof ErrorType) {
            return new MixedType();
        }

        return $inner;
    }
}
