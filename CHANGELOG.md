# Changelog

All notable changes to `filament-notification-center` will be documented in this file.

## 1.0.0 - 2026-07-10

Initial release.

- Categorized notification drawer with tabs, replacing Filament's flat chronological list.
- `Notification::make()->category(...)` macro — categorize notifications without changing how they're sent, no migration required.
- Per-panel category registration via `FilamentNotificationCenterPlugin::make()->categories([...])`, with a global fallback via the `NotificationCenter` facade.
- Category definitions support plain objects (`NotificationCenterCategory`) or `BackedEnum` cases implementing Filament's `HasLabel`/`HasIcon`/`HasColor` contracts.
- Configurable default category for uncategorized notifications, and customizable per-category empty states.
- Built-in, config-gated "Imports" and "Exports" categories for Filament's import/export action completion notifications, via the `CategorizesImportNotifications`/`CategorizesExportNotifications` traits.
- Built entirely from Filament's own UI components, so it matches the panel's theme in both light and dark mode.
