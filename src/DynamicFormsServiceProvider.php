<?php

namespace JibayMcs\DynamicForms;

use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use JibayMcs\DynamicForms\Forms\DynamicForm;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use JibayMcs\DynamicForms\Commands\DynamicFormsCommand;
use JibayMcs\DynamicForms\Testing\TestsDynamicForms;

class DynamicFormsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'dynamic-forms';

    public static string $viewNamespace = 'dynamic-forms';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            /*->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('jibaymcs/dynamic-forms');
            })*/;

        // $configFileName = $package->shortName();

        /*if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }*/

        /*if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }*/

        /*if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }*/

        /*if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }*/
    }

    public function packageRegistered(): void
    {

    }

    public function packageBooted(): void
    {

    }

    protected function getAssetPackageName(): ?string
    {
        return 'jibaymcs/dynamic-forms';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('dynamic-forms', __DIR__ . '/../resources/dist/components/dynamic-forms.js'),
            Css::make('dynamic-forms-styles', __DIR__ . '/../resources/dist/dynamic-forms.css'),
            Js::make('dynamic-forms-scripts', __DIR__ . '/../resources/dist/dynamic-forms.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            DynamicFormsCommand::class,
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

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_dynamic-forms_table',
        ];
    }
}
