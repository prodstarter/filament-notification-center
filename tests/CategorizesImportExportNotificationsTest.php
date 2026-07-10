<?php

use Filament\Notifications\Notification;
use Prodstarter\FilamentNotificationCenter\Concerns\CategorizesExportNotifications;
use Prodstarter\FilamentNotificationCenter\Concerns\CategorizesImportNotifications;

it('does not tag the import notification when disabled', function () {
    config()->set('notification-center.imports.enabled', false);

    $importer = new class
    {
        use CategorizesImportNotifications;
    };

    $notification = $importer::categorizeImportNotification(Notification::make()->title('Import completed'));

    expect($notification->getCategory())->toBeNull();
});

it('tags the import notification with the configured category when enabled', function () {
    config()->set('notification-center.imports.enabled', true);
    config()->set('notification-center.imports.category', 'imports');

    $importer = new class
    {
        use CategorizesImportNotifications;
    };

    $notification = $importer::categorizeImportNotification(Notification::make()->title('Import completed'));

    expect($notification->getCategory())->toBe('imports');
});

it('does not tag the export notification when disabled', function () {
    config()->set('notification-center.exports.enabled', false);

    $exporter = new class
    {
        use CategorizesExportNotifications;
    };

    $notification = $exporter::categorizeExportNotification(Notification::make()->title('Export completed'));

    expect($notification->getCategory())->toBeNull();
});

it('tags the export notification with the configured category when enabled', function () {
    config()->set('notification-center.exports.enabled', true);
    config()->set('notification-center.exports.category', 'exports');

    $exporter = new class
    {
        use CategorizesExportNotifications;
    };

    $notification = $exporter::categorizeExportNotification(Notification::make()->title('Export completed'));

    expect($notification->getCategory())->toBe('exports');
});
