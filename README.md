# PHPStan rules for Option

[![CI](https://github.com/brimmar/phpstan-option-type/actions/workflows/ci.yml/badge.svg)](https://github.com/brimmar/phpstan-option-type/actions/workflows/ci.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/brimmar/phpstan-rustlike-option-extension)](https://packagist.org/packages/brimmar/phpstan-rustlike-option-extension)
[![License](https://img.shields.io/packagist/l/brimmar/phpstan-rustlike-option-extension)](https://packagist.org/packages/brimmar/phpstan-rustlike-option-extension)

A PHPStan extension for the `Option` type of [brimmar/phpresult](https://github.com/brimmar/phpresult). It needs phpresult 4, PHPStan 2.3 and PHP 8.5.

PHPStan reads the generics of `Option` from phpresult itself. This extension adds the checks below. The Result checks are in [brimmar/phpstan-rustlike-result-extension](https://github.com/brimmar/phpstan-result-type), and each extension works without the other.

## Install

```bash
composer require --dev brimmar/phpstan-rustlike-option-extension
```

```neon
includes:
    - vendor/brimmar/phpstan-rustlike-option-extension/extension.neon
```

[phpstan/extension-installer](https://github.com/phpstan/extension-installer) adds that include for you.

## Checks

`unwrap()` and `expect()` throw when the value is `None`. PHPStan accepts the call when the receiver is `Some`, including `(new Some(1))->unwrap()` and the lines after `if ($option->isNone()) return`. A bare `Option`, a check on a different variable, and the `isNone()` branch are `option.unsafeMethod`.

```php
function label(Option $option): string
{
    return $option->unwrap(); // option.unsafeMethod
}
```

```php
function label(Option $option): string
{
    if ($option->isNone()) {
        return 'missing';
    }

    return $option->unwrap();
}
```

`isSomeAnd()` narrows the true branch only. `isNone()` narrows to `None<never>`, so `return $option;` in that branch fits a function that returns an `Option` of another type.

`match()` accepts `Some` and `None` in either order. A positional call or a different name is `option.matchNamedArgs`. One argument is `option.matchArgs`.

```php
$option->match(
    None: fn () => 'missing',
    Some: fn ($value) => $value,
);
```

An unused `Option` is `option.unused`. That includes `new Some(1);` and a function of yours. The pipe operator does not hide it.

```php
/** @return Option<int> */
function find(int $id): Option
{
    return new Some($id);
}

find(1); // option.unused

$_ = find(1);
```

Methods that return an `Option` already use `#[\NoDiscard]`. Ignoring one is `method.resultDiscarded`. `(void)` acknowledges that drop. On a function without the attribute, the same cast is `function.inVoidCast`, so assign the call to `$_` instead.

```php
(void) $option->map(fn ($value) => $value);
```

`flatten()`, `transpose()`, and `unzip()` are reported when the contained type is definitely wrong. `option.flatten` wants `Option<Option<T>>`. `option.transpose` wants `Option<Result<T, E>>`. `option.unzip` wants an array. `mixed` and a template type are left alone.

```php
/** @param Option<int> $option */
function collapsed(Option $option): mixed
{
    return $option->flatten(); // option.flatten
}
```

## License

MIT. See [LICENSE.md](LICENSE.md).
