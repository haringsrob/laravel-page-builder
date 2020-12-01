<?php

namespace Haringsrob\LaravelPageBuilder;

use Haringsrob\LaravelPageBuilder\View\Components\BuilderContent;
use Haringsrob\LaravelPageBuilder\View\Components\Card;
use Haringsrob\LaravelPageBuilder\View\Components\DoubleColumn;
use Haringsrob\LaravelPageBuilder\View\Components\Heading;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;

class LaravelPageBuilderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-page-builder');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-page-builder');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');
        //
        Nova::serving(function () {
            Nova::script('laravel-page-builder-field', __DIR__ . '/../dist/js/field.js');
            Nova::style('laravel-page-builder-field', __DIR__ . '/../dist/css/field.css');
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-page-builder.php'),
            ], 'config');

            // Publishing the views.
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/laravel-page-builder'),
            ], 'views');

            if (!class_exists('CreatePageBuilderPagesTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_page_builder_pages_table.php.stub' => database_path(
                        'migrations/' . date('Y_m_d_His', time()) . '_create_page_builder_pages_table.php'
                    ),
                ], 'migrations');
            }

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-page-builder'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-page-builder'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-page-builder');
        $this->loadViewComponentsAs('laravel-page-builder', [
            // Main.
            BuilderContent::class,
            // Simple.
            Heading::class,
            // Structural.
            DoubleColumn::class,
            // Card
            Card::class,
        ]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-page-builder');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-page-builder', function () {
            return new LaravelPageBuilder;
        });
    }
}
