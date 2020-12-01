<?php

namespace Haringsrob\LaravelPageBuilder\Tests\Fixtures\Components;

use Haringsrob\LaravelPageBuilder\View\BuilderComponent;
use Illuminate\Contracts\View\View;

class HeadingTestComponent extends BuilderComponent
{
    protected string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public static function getForm(): array
    {
        return [
            'content' => BuilderComponent::TYPE_TEXT
        ];
    }

    public function renderForBuilder(): View
    {
        return $this->render();
    }

    public function render(): View
    {
        return view('heading-test-component', ['content' => $this->content]);
    }
}
