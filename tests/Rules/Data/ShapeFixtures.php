<?php

namespace Brimmar\PHPStan\Tests\Rules\Data;

use Brimmar\PhpOption\Interfaces\Option;
use Brimmar\PhpOption\None;
use Brimmar\PhpOption\Some;
use Brimmar\PhpResult\Interfaces\Result;

class ShapeFixtures
{
    /** @param Option<int> $option */
    public function badFlatten(Option $option): void
    {
        $option->flatten();
    }

    /** @param Option<Option<int>> $option */
    public function goodFlatten(Option $option): void
    {
        $option->flatten();
    }

    /** @param Some<int>|None<int> $option */
    public function unionFlatten(Some|None $option): void
    {
        $option->flatten();
    }

    /** @param Option<mixed> $option */
    public function mixedFlatten(Option $option): void
    {
        $option->flatten();
    }

    /**
     * @template T
     *
     * @param Option<T> $option
     */
    public function templateFlatten(Option $option): void
    {
        $option->flatten();
    }

    /** @param Option<int> $option */
    public function badUnzip(Option $option): void
    {
        $option->unzip();
    }

    /** @param Option<array{int, string}> $option */
    public function goodUnzip(Option $option): void
    {
        $option->unzip();
    }

    /** @param Some<int>|None<int> $option */
    public function unionUnzip(Some|None $option): void
    {
        $option->unzip();
    }

    /** @param Option<int> $option */
    public function badTranspose(Option $option): void
    {
        $option->transpose();
    }

    /** @param Option<Result<int, string>> $option */
    public function goodTranspose(Option $option): void
    {
        $option->transpose();
    }
}
