<?php

use Prodstarter\FilamentNotificationCenter\NotificationCenterCategory;
use Prodstarter\FilamentNotificationCenter\Tests\Fixtures\NotificationCategoryEnum;

it('defaults the label to a headline-cased id', function () {
    expect(NotificationCenterCategory::make('customer_support')->getLabel())->toBe('Customer Support');
});

it('uses the configured label, icon, color, and order', function () {
    $category = NotificationCenterCategory::make('orders')
        ->label('Orders')
        ->icon('heroicon-o-shopping-bag')
        ->color('amber')
        ->order(2);

    expect($category->getId())->toBe('orders')
        ->and($category->getLabel())->toBe('Orders')
        ->and($category->getIcon())->toBe('heroicon-o-shopping-bag')
        ->and($category->getColor())->toBe('amber')
        ->and($category->getOrder())->toBe(2);
});

it('builds a category from a backed enum implementing HasLabel, HasIcon, and HasColor', function () {
    $category = NotificationCenterCategory::fromEnum(NotificationCategoryEnum::Orders);

    expect($category->getId())->toBe('orders')
        ->and($category->getLabel())->toBe('Orders')
        ->and($category->getIcon())->toBe('heroicon-o-shopping-bag')
        ->and($category->getColor())->toBe('amber');
});
