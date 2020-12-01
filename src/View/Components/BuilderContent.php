<?php

namespace Haringsrob\LaravelPageBuilder\View\Components;

use Haringsrob\LaravelPageBuilder\PageBuilderCollection;
use Haringsrob\LaravelPageBuilder\View\BuilderComponent;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BuilderContent extends Component
{
    protected PageBuilderCollection $builderContent;

    public function __construct(PageBuilderCollection $content)
    {
        $this->builderContent = $content;
    }

    public function render(): View
    {
        $output = '';

        $this->builderContent->each(function (Component $component) use (&$output) {
            if ($component instanceof BuilderComponent) {
                $output .= $component->renderForBuilder();
            } else {
                // @todo: Currently there is no support for non builderComponent.
                $output .= $component->render();
            }
        });

        return view('laravel-page-builder::components.builder-content', ['content' => $output]);
    }
}
