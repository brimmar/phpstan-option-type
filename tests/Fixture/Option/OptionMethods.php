<?php

declare(strict_types=1);

namespace Brimmar\PhpOption;

use Brimmar\PhpOption\Interfaces\Option;

trait OptionMethods
{
    public function isSome(): bool
    {
        return true;
    }

    public function isSomeAnd(callable $fn): bool
    {
        return true;
    }

    public function isNone(): bool
    {
        return false;
    }

    public function unwrap(): mixed
    {
        return null;
    }

    public function expect(string $message): mixed
    {
        return null;
    }

    public function flatten(): Option
    {
        throw new \RuntimeException('stub');
    }

    public function transpose(): mixed
    {
        return null;
    }

    public function unzip(): array
    {
        return [];
    }

    public function match(mixed $Some, mixed $None): mixed
    {
        return null;
    }
}
