<?php

namespace Haringsrob\LaravelPageBuilder\View\Components;

use Haringsrob\LaravelPageBuilder\View\BuilderComponent;
use Illuminate\Contracts\View\View;

class Card extends BuilderComponent
{
    protected string $content;
    protected string $footer;
    protected BuilderComponent $child;

    public function __construct(string $content, BuilderComponent $child, string $footer)
    {
        $this->content = $content;
        $this->child = $child;
        $this->footer = $footer;
    }

    public static function getForm(): array
    {
        return [
            'content' => BuilderComponent::TYPE_TEXT,
            'child' => BuilderComponent::TYPE_CHILD,
            'footer' => BuilderComponent::TYPE_TEXT,
        ];
    }

    public function renderForBuilder(): View
    {
        return $this->render();
    }

    public function render(): View
    {
        return view('laravel-page-builder::components.card', [
            'content' => $this->content,
            'child' => $this->child->renderForBuilder(),
            'footer' => $this->footer
        ]);
    }
}
