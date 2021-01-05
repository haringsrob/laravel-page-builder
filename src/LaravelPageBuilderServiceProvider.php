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
        // Only register the nova field if it is available.
        if (class_exists('Laravel\Nova\Nova')) {
            Laravel\Nova\Nova::serving(
                function () {
                    Laravel\Nova\Nova::script('laravel-page-builder-field', __DIR__ . '/../dist/js/field.js');
                    Laravel\Nova\Nova::style('laravel-page-builder-field', __DIR__ . '/../dist/css/field.css');
                }
            );
            if (!class_exists('CreatePageBuilderPagesTable')) {
                $this->publishes(
                    [
                        __DIR__ . '/../database/migrations/create_page_builder_pages_table.php.stub' => database_path(
                            'migrations/' . date('Y_m_d_His', time()) . '_create_page_builder_pages_table.php'
                        ),
                    ],
                    'migrations'
                );
            }
        }

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
