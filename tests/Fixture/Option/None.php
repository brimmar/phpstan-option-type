<?php

declare(strict_types=1);

namespace Brimmar\PhpOption;

use Brimmar\PhpOption\Interfaces\Option;

/**
 * @template T
 *
 * @implements Option<T>
 */
final class None implements Option
{
    use OptionMethods;
}
