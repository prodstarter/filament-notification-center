<?php

use Prodstarter\FilamentNotificationCenter\FilamentNotificationCenter;
use Prodstarter\FilamentNotificationCenter\NotificationCenterCategory;
use Prodstarter\FilamentNotificationCenter\Tests\Fixtures\NotificationCategoryEnum;

it('sorts registered categories by order', function () {
    $manager = new FilamentNotificationCenter;

    $manager->categories([
        NotificationCenterCategory::make('billing')->order(2),
        NotificationCenterCategory::make('orders')->order(1),
    ]);

    expect($manager->getCategories()->keys()->all())->toBe(['orders', 'billing']);
});

it('accepts backed enum categories alongside NotificationCenterCategory instances', function () {
    $manager = (new FilamentNotificationCenter)->categories([
        NotificationCategoryEnum::Orders,
    ]);

    expect($manager->getCategory('orders')?->getLabel())->toBe('Orders');
});

it('falls back to the default category when resolving a blank category', function () {
    $manager = (new FilamentNotificationCenter)->defaultCategory('general');

    expect($manager->resolveCategoryId(null))->toBe('general')
        ->and($manager->resolveCategoryId(''))->toBe('general')
        ->and($manager->resolveCategoryId('orders'))->toBe('orders');
});

it('uses a custom empty state callback when provided', function () {
    $manager = new FilamentNotificationCenter;

    $manager->emptyStateUsing(fn (string $categoryId): array => [
        'heading' => "Nothing in {$categoryId}",
        'description' => 'Custom description',
    ]);

    expect($manager->getEmptyState('orders'))->toBe([
        'heading' => 'Nothing in orders',
        'description' => 'Custom description',
    ]);
});

it('falls back to translated default empty state text when no callback is set', function () {
    $manager = new FilamentNotificationCenter;

    expect($manager->getEmptyState('orders'))->toBe([
        'heading' => 'No notifications yet.',
        'description' => 'You\'re all caught up.',
    ]);
});

it('does not include the built-in imports/exports categories when disabled', function () {
    $manager = (new FilamentNotificationCenter)->categories([
        NotificationCenterCategory::make('orders'),
    ]);

    expect($manager->getCategories()->keys()->all())->toBe(['orders']);
});

it('includes an enabled built-in category from config', function () {
    config()->set('notification-center.imports.enabled', true);

    $manager = (new FilamentNotificationCenter)->categories([
        NotificationCenterCategory::make('orders')->order(1),
    ]);

    $categories = $manager->getCategories();

    expect($categories->keys()->all())->toBe(['orders', 'imports'])
        ->and($categories->get('imports')->getLabel())->toBe('Imports');
});

it('lets an explicitly registered category override a built-in one with the same id', function () {
    config()->set('notification-center.imports.enabled', true);

    $manager = (new FilamentNotificationCenter)->categories([
        NotificationCenterCategory::make('imports')->label('Custom Imports'),
    ]);

    expect($manager->getCategories()->get('imports')->getLabel())->toBe('Custom Imports');
});
