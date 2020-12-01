<?php

namespace Haringsrob\LaravelPageBuilder\View\Components;

use Haringsrob\LaravelPageBuilder\View\BuilderComponent;
use Illuminate\Contracts\View\View;

class DoubleColumn extends BuilderComponent
{
    protected array $left;
    protected array $right;

    /**
     * @param BuilderComponent[] $left
     * @param BuilderComponent[] $right
     */
    public function __construct(array $left, array $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    public static function getForm(): array
    {
        return [
            'left' => BuilderComponent::TYPE_CHILDREN,
            'right' => BuilderComponent::TYPE_CHILDREN
        ];
    }

    public function renderForBuilder(): View
    {
        $leftRendered = '';
        $rightRendered = '';
        foreach ($this->left as $left) {
            $leftRendered .= $left->renderForBuilder();
        }
        foreach ($this->right as $right) {
            $rightRendered .= $right->renderForBuilder();
        }

        return view('laravel-page-builder::components.double-column', ['left' => $leftRendered, 'right' => $rightRendered]);
    }

    public function render(): View
    {
        return view('laravel-page-builder::components.double-column', ['left' => $this->left, 'right' => $this->right]);
    }
}
