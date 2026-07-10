<?php

namespace Prodstarter\FilamentNotificationCenter\Concerns;

use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;

/**
 * Add to a Filament Exporter class to file its completion notification under
 * the "Exports" category tab, when enabled via the `notification-center.exports`
 * config. If the Exporter already overrides modifyCompletedNotification(), call
 * static::categorizeExportNotification($notification) from within it instead of
 * using this trait.
 */
trait CategorizesExportNotifications
{
    public static function modifyCompletedNotification(Notification $notification, Export $export): Notification
    {
        return static::categorizeExportNotification($notification);
    }

    public static function categorizeExportNotification(Notification $notification): Notification
    {
        if (! config('notification-center.exports.enabled', false)) {
            return $notification;
        }

        return $notification->category(config('notification-center.exports.category', 'exports'));
    }
}
