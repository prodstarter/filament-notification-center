<?php

namespace Prodstarter\FilamentNotificationCenter\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Prodstarter\FilamentNotificationCenter\FilamentNotificationCenter
 */
class FilamentNotificationCenter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Prodstarter\FilamentNotificationCenter\FilamentNotificationCenter::class;
    }
}
