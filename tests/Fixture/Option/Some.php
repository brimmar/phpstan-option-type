<?php

declare(strict_types=1);

namespace Brimmar\PhpOption;

/**
 * @template-covariant T
 *
 * @extends Option<T>
 */
final class Some extends Option
{
    use OptionMethods;

    public function __construct(private mixed $value = null) {}
}
