<?php

namespace Prodstarter\FilamentNotificationCenter;

use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Prodstarter\FilamentNotificationCenter\Commands\FilamentNotificationCenterCommand;
use Prodstarter\FilamentNotificationCenter\Testing\TestsFilamentNotificationCenter;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentNotificationCenterServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-notification-center';

    public static string $viewNamespace = 'filament-notification-center';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('prodstarter/filament-notification-center');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(FilamentNotificationCenter::class, fn () => (new FilamentNotificationCenter)
            ->defaultCategory(config('notification-center.default_category', 'general')));
    }

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-notification-center/{$file->getFilename()}"),
                ], 'filament-notification-center-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentNotificationCenter);

        $this->registerNotificationCategoryMacros();
    }

    protected function registerNotificationCategoryMacros(): void
    {
        Notification::macro('category', function (string | BackedEnum | null $category): static {
            /** @var Notification $this */
            $this->viewData(['category' => $category instanceof BackedEnum ? $category->value : $category]);

            return $this;
        });

        Notification::macro('getCategory', function (): ?string {
            /** @var Notification $this */
            return $this->getViewData()['category'] ?? null;
        });
    }

    protected function getAssetPackageName(): ?string
    {
        return 'prodstarter/filament-notification-center';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-notification-center', __DIR__ . '/../resources/dist/components/filament-notification-center.js'),
            // Css::make('filament-notification-center-styles', __DIR__ . '/../resources/dist/filament-notification-center.css'),
            // Js::make('filament-notification-center-scripts', __DIR__ . '/../resources/dist/filament-notification-center.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentNotificationCenterCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }
}
