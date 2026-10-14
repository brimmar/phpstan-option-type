<?php

declare(strict_types=1);

namespace Brimmar\PhpOption\Interfaces;

use Brimmar\PhpOption\None;
use Brimmar\PhpOption\Some;

/**
 * @template T
 */
interface Option
{
    /**
     * @phpstan-assert-if-true Some<T> $this
     */
    public function isSome(): bool;

    /**
     * @param callable(T): bool $fn
     *
     * @phpstan-assert-if-true Some<T> $this
     */
    public function isSomeAnd(callable $fn): bool;

    /**
     * @phpstan-assert-if-true None<T> $this
     */
    public function isNone(): bool;

    public function unwrap(): mixed;

    public function expect(string $message): mixed;

    public function flatten(): Option;

    public function transpose(): mixed;

    /**
     * @return array{Option, Option}
     */
    public function unzip(): array;

    public function match(mixed $Some, mixed $None): mixed;
}
