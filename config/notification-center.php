<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Category
    |--------------------------------------------------------------------------
    |
    | The category ID that notifications sent without an explicit ->category()
    | are grouped under in the notification center drawer.
    |
    */

    'default_category' => 'general',

    /*
    |--------------------------------------------------------------------------
    | Import Notifications
    |--------------------------------------------------------------------------
    |
    | When enabled, a category tab is added automatically for the completion
    | notifications sent by Filament's import action. This only tags the
    | notification if the Importer class also uses the
    | Prodstarter\FilamentNotificationCenter\Concerns\CategorizesImportNotifications
    | trait — enabling it here just controls whether the tab exists and how
    | it looks.
    |
    */

    'imports' => [
        'enabled' => false,
        'category' => 'imports',
        'label' => 'Imports',
        'icon' => 'heroicon-o-arrow-up-tray',
        'color' => 'info',
        'order' => 90,
    ],

    /*
    |--------------------------------------------------------------------------
    | Export Notifications
    |--------------------------------------------------------------------------
    |
    | When enabled, a category tab is added automatically for the completion
    | notifications sent by Filament's export action. This only tags the
    | notification if the Exporter class also uses the
    | Prodstarter\FilamentNotificationCenter\Concerns\CategorizesExportNotifications
    | trait — enabling it here just controls whether the tab exists and how
    | it looks.
    |
    */

    'exports' => [
        'enabled' => false,
        'category' => 'exports',
        'label' => 'Exports',
        'icon' => 'heroicon-o-arrow-down-tray',
        'color' => 'success',
        'order' => 91,
    ],

];
