<?php

namespace Brimmar\PHPStan\Tests\Rules\Data;

use Brimmar\PhpOption\Option;
use Brimmar\PhpOption\None;
use Brimmar\PhpOption\Some;
use function PHPStan\Testing\assertType;

/** @param Option<int> $option */
function optionBranches(Option $option): void
{
    if ($option->isSome()) {
        assertType('Brimmar\PhpOption\Some<int>', $option);
    } else {
        assertType('Brimmar\PhpOption\None<*NEVER*>', $option);
    }

    if ($option->isNone()) {
        assertType('Brimmar\PhpOption\None<*NEVER*>', $option);
    } else {
        assertType('Brimmar\PhpOption\Some<int>', $option);
    }
}

/** @param Some<int>|None<int> $option */
function optionUnion(Some|None $option): void
{
    if ($option->isSome()) {
        assertType('Brimmar\PhpOption\Some<int>', $option);
    } else {
        assertType('Brimmar\PhpOption\None<int>', $option);
    }
}

/**
 * The narrowed None carries no type, so it passes as the Option of another one.
 *
 * @param Option<int> $option
 * @return Option<string>
 */
function optionEarlyReturnsTheNone(Option $option): Option
{
    if ($option->isNone()) {
        return $option;
    }

    assertType('Brimmar\PhpOption\Some<int>', $option);

    return new Some('value');
}
