<?php

namespace Brimmar\PHPStan\Tests\Rules\Data;

use Brimmar\PhpOption\Interfaces\Option;
use Brimmar\PhpOption\Some;

function findOption(): Option
{
    throw new \RuntimeException('missing');
}

class UnusedFixtures
{
    public function __construct(public Option $option) {}

    public function dropped(): void
    {
        findOption();
    }

    public function silencedAssign(): void
    {
        $_ = findOption();
    }

    public function silencedVoid(): void
    {
        (void) findOption();
    }

    public function freshSome(): void
    {
        new Some(1);
    }

    /** @param Option<int> $option */
    public function bareVariable(Option $option): void
    {
        $option;
    }

    public function bareProperty(): void
    {
        $this->option;
    }

    #[\NoDiscard]
    public static function makeNd(): Option
    {
        throw new \RuntimeException('missing');
    }

    public static function make(): Option
    {
        throw new \RuntimeException('missing');
    }

    public function staticCalls(): void
    {
        self::makeNd();
        static::makeNd();
        self::make();
    }
}
