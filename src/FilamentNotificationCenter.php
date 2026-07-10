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
            ->sortBy(fn (NotificationCenterCategory $category): int => $category->getOrder())
            ->keyBy(fn (NotificationCenterCategory $category): string => $category->getId());

        return $this;
    }

    public function hasCategories(): bool
    {
        return filled($this->categories);
    }

    /**
     * @return Collection<string, NotificationCenterCategory>
     */
    public function getCategories(): Collection
    {
        return $this->categories ?? collect();
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
