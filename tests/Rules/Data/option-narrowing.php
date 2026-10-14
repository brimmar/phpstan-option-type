<?php

namespace Brimmar\PHPStan\Tests\Rules\Data;

use Brimmar\PhpOption\Interfaces\Option;
use Brimmar\PhpOption\None;
use Brimmar\PhpOption\Some;
use function PHPStan\Testing\assertType;

/** @param Option<int> $option */
function optionBranches(Option $option): void
{
    if ($option->isSome()) {
        assertType('Brimmar\PhpOption\Some<int>', $option);
    } else {
        assertType('Brimmar\PhpOption\None<int>', $option);
    }

    if ($option->isNone()) {
        assertType('Brimmar\PhpOption\None<int>', $option);
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
