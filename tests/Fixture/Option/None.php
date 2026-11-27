<?php

declare(strict_types=1);

namespace Brimmar\PhpOption;

/**
 * @template-covariant T = never
 *
 * @extends Option<T>
 */
final class None extends Option
{
    use OptionMethods;
}
