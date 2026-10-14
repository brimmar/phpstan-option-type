<?php

namespace Brimmar\PHPStan\Tests\Rules\Data;

use Brimmar\PhpOption\Interfaces\Option;

class MatchFixtures
{
    public function positional(Option $option): void
    {
        $option->match(fn () => 1, fn () => 2);
    }

    public function eitherOrder(Option $option): void
    {
        $option->match(None: fn () => 1, Some: fn (mixed $value) => $value);
        $option->match(Some: fn (mixed $value) => $value, None: fn () => 1);
    }

    public function wrongNames(Option $option): void
    {
        $option->match(Ok: fn () => 1, Err: fn () => 2);
    }

    public function oneArg(Option $option): void
    {
        $option->match(Some: fn (mixed $value) => $value);
    }
}
