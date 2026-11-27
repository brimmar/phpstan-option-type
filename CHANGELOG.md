# Changelog

## [3.0.0] - 2026-11-27

### Changed
- Works with brimmar/phpresult 4, where `Option` is the class `Brimmar\PhpOption\Option` and `Result` is `Brimmar\PhpResult\Result`. Composer refuses older versions of that package, because the extension would find no `Option` in them and report nothing.
- `isNone()` and the else branch of `isSome()` narrow to `None<never>`. A `None` narrowed this way is accepted as an `Option` of any other type, so `if ($option->isNone()) { return $option; }` passes.

## [2.0.0] - 2026-10-13

### Changed
- Requires PHP 8.5 and PHPStan 2.3.
- `option.matchOrder` is gone. Ignore `option.matchNamedArgs` instead.
- `isSome()` and `isNone()` narrow both branches to `Some<T>` and `None<T>`.
- `option.unused`, `option.flatten`, `option.transpose`, and `option.unzip` are new errors.
- `unwrap()` and `expect()` on a definite `None` say that None has no value.

[3.0.0]: https://github.com/brimmar/phpstan-option-type/compare/v2.0.9...v3.0.0
[2.0.0]: https://github.com/brimmar/phpstan-option-type/compare/v1.0.3...v2.0.0
