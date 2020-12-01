<?php

namespace Haringsrob\LaravelPageBuilder;

use Haringsrob\LaravelPageBuilder\View\Components\BuilderContent;
use Illuminate\Support\Collection;

class PageBuilderCollection extends Collection
{
    /**
     * The components to render.
     *
     * @var BuilderContent[]
     */
    protected $items = [];
}
