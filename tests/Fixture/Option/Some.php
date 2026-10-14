<?php

declare(strict_types=1);

namespace Brimmar\PhpOption;

use Brimmar\PhpOption\Interfaces\Option;

/**
 * @template T
 *
 * @implements Option<T>
 */
final class Some implements Option
{
    use OptionMethods;

    public function __construct(private mixed $value = null) {}
}
