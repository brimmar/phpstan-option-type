# Changelog

## [Unreleased]

## [2.0.0] - 2026-10-13

### Changed
- Requires PHP 8.5 and PHPStan 2.3.
- `option.matchOrder` is gone. Ignore `option.matchNamedArgs` instead.
- `isSome()` and `isNone()` narrow both branches to `Some<T>` and `None<T>`.
- `option.unused`, `option.flatten`, `option.transpose`, and `option.unzip` are new errors.
- `unwrap()` and `expect()` on a definite `None` say that None has no value.

[Unreleased]: https://github.com/brimmar/phpstan-option-type/compare/v2.0.0...HEAD
[2.0.0]: https://github.com/brimmar/phpstan-option-type/compare/v1.0.3...v2.0.0
