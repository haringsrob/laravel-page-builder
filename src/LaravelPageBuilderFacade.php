<?php

namespace Haringsrob\LaravelPageBuilder;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Haringsrob\LaravelPageBuilder\Skeleton\SkeletonClass
 */
class LaravelPageBuilderFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-page-builder';
    }
}
