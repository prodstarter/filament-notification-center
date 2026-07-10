# Changelog

All notable changes to `filament-notification-center` will be documented in this file.

## Categorized Notification Center - 2026-07-10

The first stable release of Filament Notification Center — a drop-in replacement for Filament's notification drawer that organizes notifications into tabs instead of one flat list.

### Highlights

- 🗂️ **Categorized tabs** in the notification drawer — "All" plus one tab per category, each with an unread count badge.
- 🔌 **Drop-in compatible** — existing `Notification::make()->sendToDatabase($user)` code keeps working unmodified. Add `->category('orders')` to file it under a tab.
- 🧩 **Per-panel configuration** — register a different set of categories per panel via the plugin instance, with an optional global fallback.
- 🏷️ **Enum-friendly** — register categories as plain objects or as `BackedEnum` cases implementing Filament's `HasLabel`/`HasIcon`/`HasColor` contracts.
- 🎯 **No schema changes** — categories are stored in the existing notification `data` payload, so there's no migration to run.
- 📥 **Built-in import/export tabs** — enable dedicated "Imports"/"Exports" tabs for Filament's import/export action completion notifications via config + a one-line trait.
- 🎨 **Looks native** — built entirely from Filament's own UI components, so it matches your panel's theme, including dark mode.

### Installation

```bash
composer require prodstarter/filament-notification-center

See the README for full usage docs.

Full Changelog: https://github.com/prodstarter/filament-notification-center/commits/v1.0.0

```
## 1.0.0 - 2026-07-10

Initial release.

- Categorized notification drawer with tabs, replacing Filament's flat chronological list.
- `Notification::make()->category(...)` macro — categorize notifications without changing how they're sent, no migration required.
- Per-panel category registration via `FilamentNotificationCenterPlugin::make()->categories([...])`, with a global fallback via the `NotificationCenter` facade.
- Category definitions support plain objects (`NotificationCenterCategory`) or `BackedEnum` cases implementing Filament's `HasLabel`/`HasIcon`/`HasColor` contracts.
- Configurable default category for uncategorized notifications, and customizable per-category empty states.
- Built-in, config-gated "Imports" and "Exports" categories for Filament's import/export action completion notifications, via the `CategorizesImportNotifications`/`CategorizesExportNotifications` traits.
- Built entirely from Filament's own UI components, so it matches the panel's theme in both light and dark mode.
