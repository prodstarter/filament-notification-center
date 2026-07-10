<?php

namespace Prodstarter\FilamentNotificationCenter;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;

final class NotificationCenterTab
{
    public function __construct(
        public readonly string $id,
        public readonly string | Htmlable $label,
        public readonly string | BackedEnum | Htmlable | null $icon,
        public readonly string | array | null $color,
        public readonly int $count,
    ) {}
}
