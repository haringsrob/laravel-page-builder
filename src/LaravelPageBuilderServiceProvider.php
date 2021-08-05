<?php

namespace Haringsrob\LaravelPageBuilder;

use Haringsrob\LaravelPageBuilder\View\Components\BuilderContent;
use Haringsrob\LaravelPageBuilder\View\Components\Card;
use Haringsrob\LaravelPageBuilder\View\Components\DoubleColumn;
use Haringsrob\LaravelPageBuilder\View\Components\Heading;
use Illuminate\Support\ServiceProvider;

class LaravelPageBuilderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__ . '/../config/config.php' => config_path('laravel-page-builder.php'),
                ],
                'config'
            );

            // Publishing the views.
            $this->publishes(
                [
                    __DIR__ . '/../resources/views' => resource_path('views/vendor/laravel-page-builder'),
                ],
                'views'
            );
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-page-builder');
        $this->loadViewComponentsAs(
            'laravel-page-builder',
            [
                // Main.
                BuilderContent::class,
                // Simple.
                Heading::class,
                // Structural.
                DoubleColumn::class,
                // Card
                Card::class,
            ]
        );
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-page-builder');
    }
}
