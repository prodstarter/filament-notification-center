<?php

namespace Prodstarter\FilamentNotificationCenter\Testing;

use Closure;
use Livewire\Features\SupportTesting\Testable;
use PHPUnit\Framework\Assert;

/**
 * @mixin Testable
 */
class TestsFilamentNotificationCenter
{
    public function assertActiveNotificationCategory(): Closure
    {
        return function (string $categoryId) {
            /** @var Testable $this */
            $this->assertSet('activeCategory', $categoryId);

            return $this;
        };
    }

    public function assertNotificationCategoryTabCount(): Closure
    {
        return function (string $categoryId, int $count) {
            /** @var Testable $this */
            $tab = $this->instance()->categoryTabs->firstWhere('id', $categoryId);

            Assert::assertNotNull($tab, "No notification category tab exists with id [{$categoryId}].");
            Assert::assertSame($count, $tab->count, "Expected [{$count}] unread notifications in the [{$categoryId}] category, got [{$tab->count}].");

            return $this;
        };
    }
}
