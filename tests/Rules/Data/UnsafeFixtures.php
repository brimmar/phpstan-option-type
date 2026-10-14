<?php

namespace Brimmar\PHPStan\Tests\Rules\Data;

use Brimmar\PhpOption\Interfaces\Option;
use Brimmar\PhpOption\None;
use Brimmar\PhpOption\Some;

class UnsafeFixtures
{
    public function bare(Option $option): void
    {
        $option->unwrap();
    }

    public function some(): void
    {
        (new Some(1))->unwrap();
    }

    public function none(): void
    {
        (new None())->unwrap();
    }

    public function checked(Option $option): void
    {
        if ($option->isSome()) {
            $option->unwrap();
        } else {
            $option->unwrap();
        }
    }

    public function early(Option $option): void
    {
        if ($option->isNone()) {
            return;
        }

        $option->unwrap();
    }

    public function expectNone(): void
    {
        (new None())->expect('missing');
    }
}
