# Changelog

All notable changes to `filament-notification-center` will be documented in this file.

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
