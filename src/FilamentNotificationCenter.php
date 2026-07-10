<?php

namespace Prodstarter\FilamentNotificationCenter;

use BackedEnum;
use Closure;
use Illuminate\Support\Collection;

class FilamentNotificationCenter
{
    /**
     * @var Collection<string, NotificationCenterCategory> | null
     */
    protected ?Collection $categories = null;

    protected string $defaultCategory = 'general';

    protected ?Closure $emptyStateUsing = null;

    /**
     * @param  array<NotificationCenterCategory | BackedEnum>  $categories
     */
    public function categories(array $categories): static
    {
        $this->categories = collect($categories)
            ->map(fn (NotificationCenterCategory | BackedEnum $category): NotificationCenterCategory => $category instanceof BackedEnum
                ? NotificationCenterCategory::fromEnum($category)
                : $category)
            ->keyBy(fn (NotificationCenterCategory $category): string => $category->getId());

        return $this;
    }

    public function hasCategories(): bool
    {
        return filled($this->categories);
    }

    /**
     * The registered categories, merged with any config-enabled built-in
     * categories (e.g. imports/exports) that weren't already registered
     * explicitly, sorted by order.
     *
     * @return Collection<string, NotificationCenterCategory>
     */
    public function getCategories(): Collection
    {
        $categories = $this->categories ?? collect();

        $builtIn = $this->getBuiltInCategories()->reject(
            fn (NotificationCenterCategory $category): bool => $categories->has($category->getId())
        );

        return $categories->merge($builtIn)
            ->sortBy(fn (NotificationCenterCategory $category): int => $category->getOrder())
            ->values()
            ->keyBy(fn (NotificationCenterCategory $category): string => $category->getId());
    }

    /**
     * Categories that ship with the plugin (import/export completion
     * notifications) and are enabled via config rather than ->categories().
     *
     * @return Collection<string, NotificationCenterCategory>
     */
    protected function getBuiltInCategories(): Collection
    {
        return collect([
            'imports' => config('notification-center.imports'),
            'exports' => config('notification-center.exports'),
        ])
            ->filter(fn (?array $definition): bool => (bool) ($definition['enabled'] ?? false))
            ->map(function (array $definition, string $key): NotificationCenterCategory {
                $id = $definition['category'] ?? $key;

                return NotificationCenterCategory::make($id)
                    ->label($definition['label'] ?? null)
                    ->icon($definition['icon'] ?? null)
                    ->color($definition['color'] ?? null)
                    ->order($definition['order'] ?? 100);
            })
            ->keyBy(fn (NotificationCenterCategory $category): string => $category->getId());
    }

    public function getCategory(string $id): ?NotificationCenterCategory
    {
        return $this->getCategories()->get($id);
    }

    public function defaultCategory(string $id): static
    {
        $this->defaultCategory = $id;

        return $this;
    }

    public function getDefaultCategory(): string
    {
        return $this->defaultCategory;
    }

    /**
     * Resolve the category a stored notification belongs to, falling back to the
     * configured default category when the notification wasn't sent with one.
     */
    public function resolveCategoryId(?string $rawCategory): string
    {
        return filled($rawCategory) ? $rawCategory : $this->getDefaultCategory();
    }

    /**
     * @param  Closure(string $categoryId): array{heading: string, description: string}  $callback
     */
    public function emptyStateUsing(Closure $callback): static
    {
        $this->emptyStateUsing = $callback;

        return $this;
    }

    /**
     * @return array{heading: string, description: string}
     */
    public function getEmptyState(string $categoryId): array
    {
        if ($this->emptyStateUsing) {
            return ($this->emptyStateUsing)($categoryId);
        }

        return [
            'heading' => __('filament-notification-center::notification-center.empty.heading'),
            'description' => __('filament-notification-center::notification-center.empty.description'),
        ];
    }
}
