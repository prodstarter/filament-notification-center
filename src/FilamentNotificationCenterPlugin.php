<?php

namespace Prodstarter\FilamentNotificationCenter;

use BackedEnum;
use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Prodstarter\FilamentNotificationCenter\Livewire\NotificationCenter;

class FilamentNotificationCenterPlugin implements Plugin
{
    protected FilamentNotificationCenter $categoryRegistry;

    public function __construct()
    {
        $this->categoryRegistry = (new FilamentNotificationCenter)
            ->defaultCategory(config('notification-center.default_category', 'general'));
    }

    public function getId(): string
    {
        return 'filament-notification-center';
    }

    public function register(Panel $panel): void
    {
        $panel->databaseNotificationsLivewireComponent(NotificationCenter::class);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    /**
     * @param  array<NotificationCenterCategory | BackedEnum>  $categories
     */
    public function categories(array $categories): static
    {
        $this->categoryRegistry->categories($categories);

        return $this;
    }

    public function defaultCategory(string $id): static
    {
        $this->categoryRegistry->defaultCategory($id);

        return $this;
    }

    /**
     * @param  Closure(string $categoryId): array{heading: string, description: string}  $callback
     */
    public function emptyStateUsing(Closure $callback): static
    {
        $this->categoryRegistry->emptyStateUsing($callback);

        return $this;
    }

    /**
     * The plugin's own category registry if it was configured, otherwise the
     * globally registered FilamentNotificationCenter::categories() default.
     */
    public function getCategoryRegistry(): FilamentNotificationCenter
    {
        if ($this->categoryRegistry->hasCategories()) {
            return $this->categoryRegistry;
        }

        return app(FilamentNotificationCenter::class);
    }
}
