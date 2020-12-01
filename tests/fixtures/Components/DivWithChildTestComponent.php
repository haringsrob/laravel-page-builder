<?php

namespace Haringsrob\LaravelPageBuilder\Tests\Fixtures\Components;

use Haringsrob\LaravelPageBuilder\View\BuilderComponent;
use Illuminate\Contracts\View\View;

class DivWithChildTestComponent extends BuilderComponent
{
    protected BuilderComponent $content;

    public function __construct(BuilderComponent $content)
    {
        $this->content = $content;
    }

    public static function getForm(): array
    {
        return [
            'content' => BuilderComponent::TYPE_CHILD
        ];
    }

    public function renderForBuilder(): View
    {
        return view('div-with-child-test-component', ['content' => $this->content->renderForBuilder()]);
    }

    public function render(): View
    {
        return view('div-with-child-test-component', ['content' => $this->content]);
    }
}
