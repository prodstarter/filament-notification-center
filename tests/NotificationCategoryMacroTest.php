<?php

use Filament\Notifications\Notification;
use Prodstarter\FilamentNotificationCenter\Tests\Fixtures\NotificationCategoryEnum;
use Prodstarter\FilamentNotificationCenter\Tests\Models\User;

it('stores the category on the notification via viewData', function () {
    $notification = Notification::make()->title('Order shipped')->category('orders');

    expect($notification->getCategory())->toBe('orders')
        ->and($notification->getViewData())->toHaveKey('category', 'orders');
});

it('accepts a backed enum as the category', function () {
    $notification = Notification::make()->title('Order shipped')->category(NotificationCategoryEnum::Orders);

    expect($notification->getCategory())->toBe('orders');
});

it('has no category by default', function () {
    expect(Notification::make()->title('Server restarted')->getCategory())->toBeNull();
});

it('persists the category through the database notification payload', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    Notification::make()
        ->title('Order shipped')
        ->category('orders')
        ->sendToDatabase($user);

    $stored = $user->notifications()->first();

    expect($stored->data['viewData']['category'])->toBe('orders');
});

it('does not break existing viewData when a category is also set', function () {
    $notification = Notification::make()
        ->title('Order shipped')
        ->viewData(['foo' => 'bar'])
        ->category('orders');

    expect($notification->getViewData())
        ->toHaveKey('foo', 'bar')
        ->toHaveKey('category', 'orders');
});
