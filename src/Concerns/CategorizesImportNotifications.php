<?php

namespace Prodstarter\FilamentNotificationCenter\Concerns;

use Filament\Actions\Imports\Models\Import;
use Filament\Notifications\Notification;

/**
 * Add to a Filament Importer class to file its completion notification under
 * the "Imports" category tab, when enabled via the `notification-center.imports`
 * config. If the Importer already overrides modifyCompletedNotification(), call
 * static::categorizeImportNotification($notification) from within it instead of
 * using this trait.
 */
trait CategorizesImportNotifications
{
    public static function modifyCompletedNotification(Notification $notification, Import $import): Notification
    {
        return static::categorizeImportNotification($notification);
    }

    public static function categorizeImportNotification(Notification $notification): Notification
    {
        if (! config('notification-center.imports.enabled', false)) {
            return $notification;
        }

        return $notification->category(config('notification-center.imports.category', 'imports'));
    }
}
