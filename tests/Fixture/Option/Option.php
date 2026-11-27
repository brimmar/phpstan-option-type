<?php

declare(strict_types=1);

namespace Brimmar\PhpOption;

/**
 * @template-covariant T
 */
abstract class Option
{
    /**
     * @phpstan-assert-if-true Some<T> $this
     */
    abstract public function isSome(): bool;

    /**
     * @param callable(T): bool $fn
     *
     * @phpstan-assert-if-true Some<T> $this
     */
    abstract public function isSomeAnd(callable $fn): bool;

    /**
     * @phpstan-assert-if-true None<never> $this
     */
    abstract public function isNone(): bool;

    abstract public function unwrap(): mixed;

    abstract public function expect(string $message): mixed;

    abstract public function flatten(): Option;

    abstract public function transpose(): mixed;

    /**
     * @return array{Option, Option}
     */
    abstract public function unzip(): array;

    abstract public function match(mixed $Some, mixed $None): mixed;
}
