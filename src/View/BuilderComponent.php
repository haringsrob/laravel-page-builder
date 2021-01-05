<?php

namespace Haringsrob\LaravelPageBuilder\View;

use Haringsrob\LaravelPageBuilder\Exceptions\NoBuilderFormException;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

abstract class BuilderComponent extends Component
{
    public const TYPE_CHILDREN = 'children';
    public const TYPE_CHILD = 'child';
    public const TYPE_TEXT = 'text';
    public const TYPE_WYSIWYG = 'wysiwyg';

    /**
     * This renders the component specifically for the builder. This is needed
     * because the data from the builder might be different than what a blade
     * file would pass in.
     *
     * If you have arguments of type "TYPE_CHILDREN" or "TYPE_CHILD" you might need to manually trigger the rendering.
     * @see \Haringsrob\LaravelPageBuilder\View\Components\DoubleColumn for an example.
     */
    public function renderForBuilder(): View
    {
        return $this->render();
    }

    /**
     * Array of key-values where key should match the property and value a type.
     *
     * !! It is important here that they are in the same order as the constructor
     * as they are dynamically assigned.
     *
     * Example:
     * ```
     *   return [
     *     'title' => BuilderComponent::TYPE_TEXT,
     *     'left' => BuilderComponent::TYPE_CHILDREN,
     *     'right' => BuilderComponent::TYPE_WYSIWYG
     *   ]
     * ```
     */
    static public function getForm(): array
    {
        throw new NoBuilderFormException();
    }

    /**
     * Gets the field label to use in the form. This can be overwritten but by
     * default is based on the class.
     */
    public static function getLabel(): string
    {
        $parts = explode('\\', static::class);

        $namespace = array_shift($parts);
        $name = end($parts);
        return join(" ", preg_split('/(?=[A-Z])/', "$namespace: $name"));
    }
}
