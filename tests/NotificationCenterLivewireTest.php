<?php

use Filament\Notifications\Notification;
use Prodstarter\FilamentNotificationCenter\Livewire\NotificationCenter;
use Prodstarter\FilamentNotificationCenter\Tests\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->actingAs($this->user);
});

it('shows only notifications belonging to the active category', function () {
    Notification::make()->title('Order shipped')->category('orders')->sendToDatabase($this->user);
    Notification::make()->title('New lead')->category('crm')->sendToDatabase($this->user);

    livewire(NotificationCenter::class)
        ->call('setActiveCategory', 'orders')
        ->assertSee('Order shipped')
        ->assertDontSee('New lead');
});

it('groups uncategorized notifications under the default category', function () {
    Notification::make()->title('Server restarted')->sendToDatabase($this->user);
    Notification::make()->title('New lead')->category('crm')->sendToDatabase($this->user);

    livewire(NotificationCenter::class)
        ->call('setActiveCategory', 'general')
        ->assertSee('Server restarted')
        ->assertDontSee('New lead');
});

it('shows all notifications regardless of category on the all tab', function () {
    Notification::make()->title('Order shipped')->category('orders')->sendToDatabase($this->user);
    Notification::make()->title('New lead')->category('crm')->sendToDatabase($this->user);

    livewire(NotificationCenter::class)
        ->assertSee('Order shipped')
        ->assertSee('New lead');
});

it('computes unread counts per category tab', function () {
    Notification::make()->title('Order shipped')->category('orders')->sendToDatabase($this->user);
    Notification::make()->title('Another order')->category('orders')->sendToDatabase($this->user);
    Notification::make()->title('New lead')->category('crm')->sendToDatabase($this->user);

    livewire(NotificationCenter::class)
        ->assertNotificationCategoryTabCount('orders', 2)
        ->assertNotificationCategoryTabCount('crm', 1)
        ->assertNotificationCategoryTabCount('all', 3);
});

it('tracks the active category as component state', function () {
    livewire(NotificationCenter::class)
        ->assertActiveNotificationCategory('all')
        ->call('setActiveCategory', 'orders')
        ->assertActiveNotificationCategory('orders');
});

it('scopes mark all as read to the active category only', function () {
    Notification::make()->title('Order shipped')->category('orders')->sendToDatabase($this->user);
    Notification::make()->title('New lead')->category('crm')->sendToDatabase($this->user);

    livewire(NotificationCenter::class)
        ->call('setActiveCategory', 'orders')
        ->call('markAllNotificationsAsRead');

    expect($this->user->notifications()->where('data->viewData->category', 'orders')->whereNull('read_at')->count())->toBe(0)
        ->and($this->user->notifications()->where('data->viewData->category', 'crm')->whereNull('read_at')->count())->toBe(1);
});

it('renders the empty state for a category with no notifications', function () {
    Notification::make()->title('New lead')->category('crm')->sendToDatabase($this->user);

    livewire(NotificationCenter::class)
        ->call('setActiveCategory', 'orders')
        ->assertSee('No notifications yet.');
});
