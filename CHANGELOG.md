# Changelog

All notable changes to `filament-notification-center` will be documented in this file.

## v1.1.0 - 2026-09-19

Fixes broken CI (tests were failing on every run) and README badges, and formalizes the package's actual minimum PHP version. No behavior changes to the package itself — safe to update from v1.0.1.

### Fixed

- **README badges**: the "tests" and "code style" shields.io badges pointed at workflow files that don't exist in this repo (`run-tests.yml`, `fix-php-code-style-issues.yml`), showing "repo or workflow not found". Fixed to point at the actual `tests.yml` / `fix-code-style.yml`.
- **CI tests failing on every run**: `phpunit.xml.dist` declared a `<coverage>` report block, but CI installs PHP with no coverage driver. Under PHPUnit 12, that combination raises a "No code coverage driver available" warning at bootstrap, before any test runs — and with `failOnWarning="true"` already set, that aborted the whole suite with exit code 1 and 0 tests executed. Removed the unused coverage config.
- **PHP 8.2 was never actually installable**: `composer.json` declared `"php": "^8.2"`, but Filament v5 requires `livewire ^4.1`, and the only pest tooling that supports livewire v4 requires PHP `^8.3`. On PHP 8.2, composer could only resolve to `pest-plugin-livewire` v3 (capped at livewire `^3.5.6`), which conflicts with Filament's requirement — `composer require prodstarter/filament-notification-center` failed to resolve on PHP 8.2. Bumped the minimum PHP version to `^8.3` to match what's actually installable.

**Full Changelog**: https://github.com/prodstarter/filament-notification-center/compare/v1.0.1...v1.1.0

## v1.0.1 - 2026-07-10

Fixes a broken `phpstan` CI workflow and the static analysis issues it uncovered. No public API changes — safe to update from v1.0.0.

### Fixed

- `phpstan.neon.dist` was missing the `larastan/larastan` extension include, so every CI job failed immediately with an "Invalid configuration" error before any analysis could run (larastan v3 no longer self-registers via `phpstan/extension-installer`).
- Replaced untyped `stdClass` notification tab objects with a proper `NotificationCenterTab` value object (same public properties — `id`, `label`, `icon`, `color`, `count` — no behavior change).
- Resolved the remaining PHPStan findings once analysis could actually run; `./vendor/bin/phpstan` now passes clean.

**Full Changelog**: https://github.com/prodstarter/filament-notification-center/compare/v1.0.0...v1.0.1

## 1.0.1 - 2026-07-10

Fixes a broken `phpstan` CI workflow and the static analysis issues it uncovered. No public API changes.

- Fixed `phpstan.neon.dist` missing the `larastan/larastan` extension include, which caused every CI job to fail immediately with an "Invalid configuration" error before any analysis could run (larastan v3 no longer self-registers via `phpstan/extension-installer`).
- Replaced untyped `stdClass` notification tab objects with a proper `NotificationCenterTab` value object (same public properties: `id`, `label`, `icon`, `color`, `count` — no behavior change).
- Fixed remaining PHPStan findings in `NotificationCenter` and the testing helper; `./vendor/bin/phpstan` now passes clean.

## 1.0.0 - 2026-07-10

Initial release.

- Categorized notification drawer with tabs, replacing Filament's flat chronological list.
- `Notification::make()->category(...)` macro — categorize notifications without changing how they're sent, no migration required.
- Per-panel category registration via `FilamentNotificationCenterPlugin::make()->categories([...])`, with a global fallback via the `NotificationCenter` facade.
- Category definitions support plain objects (`NotificationCenterCategory`) or `BackedEnum` cases implementing Filament's `HasLabel`/`HasIcon`/`HasColor` contracts.
- Configurable default category for uncategorized notifications, and customizable per-category empty states.
- Built-in, config-gated "Imports" and "Exports" categories for Filament's import/export action completion notifications, via the `CategorizesImportNotifications`/`CategorizesExportNotifications` traits.
- Built entirely from Filament's own UI components, so it matches the panel's theme in both light and dark mode.
