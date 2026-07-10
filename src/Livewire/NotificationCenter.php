<?php

namespace Prodstarter\FilamentNotificationCenter\Livewire;

use Filament\Livewire\DatabaseNotifications;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Prodstarter\FilamentNotificationCenter\FilamentNotificationCenter;
use Prodstarter\FilamentNotificationCenter\FilamentNotificationCenterPlugin;
use Prodstarter\FilamentNotificationCenter\NotificationCenterCategory;
use Prodstarter\FilamentNotificationCenter\NotificationCenterTab;

/**
 * @property-read Collection<int, NotificationCenterTab> $categoryTabs
 */
class NotificationCenter extends DatabaseNotifications
{
    public string $activeCategory = 'all';

    public function setActiveCategory(string $categoryId): void
    {
        $this->activeCategory = $categoryId;

        $this->resetPage('database-notifications-page');
    }

    public function getNotificationsQuery(): Builder | Relation
    {
        $query = $this->getBaseNotificationsQuery();

        if ($this->activeCategory === 'all') {
            return $query;
        }

        return $this->scopeQueryToCategory($query, $this->activeCategory);
    }

    /**
     * @return Collection<int, NotificationCenterTab>
     */
    #[Computed]
    public function categoryTabs(): Collection
    {
        $registry = $this->getCategoryRegistry();
        $defaultCategoryId = $registry->getDefaultCategory();

        $categories = $registry->getCategories()->values();

        if (! $registry->getCategories()->has($defaultCategoryId)) {
            $categories->push(NotificationCenterCategory::make($defaultCategoryId));
        }

        $tabs = collect([
            new NotificationCenterTab(
                id: 'all',
                label: (string) __('filament-notification-center::notification-center.tabs.all'),
                icon: null,
                color: null,
                count: $this->getBaseNotificationsQuery()->whereNull('read_at')->count(),
            ),
        ]);

        foreach ($categories as $category) {
            $tabs->push(new NotificationCenterTab(
                id: $category->getId(),
                label: $category->getLabel(),
                icon: $category->getIcon(),
                color: $category->getColor(),
                count: $this->scopeQueryToCategory($this->getBaseNotificationsQuery(), $category->getId())
                    ->whereNull('read_at')
                    ->count(),
            ));
        }

        return $tabs;
    }

    protected function getBaseNotificationsQuery(): Builder | Relation
    {
        return parent::getNotificationsQuery();
    }

    protected function scopeQueryToCategory(Builder | Relation $query, string $categoryId): Builder | Relation
    {
        if ($categoryId === $this->getCategoryRegistry()->getDefaultCategory()) {
            return $query->where(function (Builder | Relation $query) use ($categoryId) {
                $query->whereNull('data->viewData->category')
                    ->orWhere('data->viewData->category', $categoryId);
            });
        }

        return $query->where('data->viewData->category', $categoryId);
    }

    protected function getCategoryRegistry(): FilamentNotificationCenter
    {
        return FilamentNotificationCenterPlugin::get()->getCategoryRegistry();
    }

    public function hasAnyNotifications(): bool
    {
        return $this->getBaseNotificationsQuery()->exists();
    }

    /**
     * @return array{heading: string, description: string}
     */
    public function getCategoryEmptyState(string $categoryId): array
    {
        return $this->getCategoryRegistry()->getEmptyState($categoryId);
    }

    public function render(): View
    {
        return view('filament-notification-center::livewire.notification-center');
    }
}
