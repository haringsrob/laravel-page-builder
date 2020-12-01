<?php

namespace Haringsrob\LaravelPageBuilder\Tests\Fixtures\Components;

use Haringsrob\LaravelPageBuilder\View\BuilderComponent;
use Illuminate\Contracts\View\View;

class DivWithManyChildTestComponent extends BuilderComponent
{
    protected array $content;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public static function getForm(): array
    {
        return [
            'content' => BuilderComponent::TYPE_CHILDREN
        ];
    }

    public function renderForBuilder(): View
    {
        $buildContent = '';
        foreach ($this->content as $contentItem) {
            $buildContent .= $contentItem->renderForBuilder();
        }
        return view('div-with-child-test-component', ['content' => $buildContent]);
    }

    public function render(): View
    {
        return view('div-with-child-test-component', ['content' => $this->content]);
    }
}
