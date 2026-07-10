<?php

namespace Prodstarter\FilamentNotificationCenter\Tests\Fixtures;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum NotificationCategoryEnum: string implements HasColor, HasIcon, HasLabel
{
    case Orders = 'orders';
    case Crm = 'crm';

    public function getLabel(): string
    {
        return match ($this) {
            self::Orders => 'Orders',
            self::Crm => 'CRM',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Orders => 'heroicon-o-shopping-bag',
            self::Crm => 'heroicon-o-users',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Orders => 'amber',
            self::Crm => 'success',
        };
    }
}
