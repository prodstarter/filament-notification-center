<?php

namespace Prodstarter\FilamentNotificationCenter\Commands;

use Illuminate\Console\Command;

class FilamentNotificationCenterCommand extends Command
{
    public $signature = 'filament-notification-center';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
