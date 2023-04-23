<?php

namespace Mstfkhazaal\FilamentNestedresources;

use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;

class FilamentNestedresourcesServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-nestedresources';

    protected array $resources = [
        // CustomResource::class,
    ];

    protected array $pages = [
        // CustomPage::class,
    ];

    protected array $widgets = [
        // CustomWidget::class,
    ];

    protected array $styles = [
        'plugin-filament-nestedresources' => __DIR__.'/../resources/dist/filament-nestedresources.css',
    ];

    protected array $scripts = [
        'plugin-filament-nestedresources' => __DIR__.'/../resources/dist/filament-nestedresources.js',
    ];

    // protected array $beforeCoreScripts = [
    //     'plugin-filament-nestedresources' => __DIR__ . '/../resources/dist/filament-nestedresources.js',
    // ];

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name);
    }
}
