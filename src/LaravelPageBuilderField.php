<?php

namespace Haringsrob\LaravelPageBuilder;

use Haringsrob\LaravelPageBuilder\Models\Contracts\PageBuilderContract;
use Haringsrob\LaravelPageBuilder\Models\PageBuilderPage;
use Haringsrob\LaravelPageBuilder\View\BuilderComponent;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class LaravelPageBuilderField extends Field
{
    public $component = 'laravel-page-builder-field';

    protected array $builderComponents = [];

    private function getBuilderComponents(): array
    {
        if (empty($this->builderComponents)) {

            // [name => class]
            $components = app('blade.compiler')->getClassComponentAliases();

            $builderComponents = [];
            foreach ($components as $name => $class) {
                if (is_subclass_of($class, BuilderComponent::class)) {
                    $builderComponents[$name] = [
                        'class' => $class,
                        'label' => $class::getLabel(),
                        'fields' => $class::getForm(),
                    ];
                }
            }

            $this->builderComponents = $builderComponents;
        }
        return $this->builderComponents;
    }

    public function resolve($resource, $attribute = null)
    {
        // Add the meta information.
        $this->meta = array_merge($this->meta, ['available_components' => $this->getBuilderComponents()]);

        // Set the resource (as in parent.).
        $this->resource = $resource;

        $model = $this->resource;
        if ($model && $model instanceof PageBuilderContract) {
            if ($model->pageBuilderPage()->exists()) {
                $this->value = json_encode($model->pageBuilderPage->structure);
            } else {
                $this->value = '{"data":[]}';
            }
            return;
        }

        throw new \Exception('This model does not implement the page builder contract.');
    }

    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {
        if ($request->exists($requestAttribute)) {
            $model = $this->resource;
            if ($model && $model instanceof PageBuilderContract) {
                if ($model->pageBuilderPage()->exists()) {
                    $pageBuilderPage = $model->pageBuilderPage;
                } else {
                    $pageBuilderPage = PageBuilderPage::make();
                }
                $pageBuilderPage->structure = json_decode($request[$requestAttribute]);
                $pageBuilderPage->save();
            }
            $model->{$attribute} = $pageBuilderPage->id;
        }
    }
}
