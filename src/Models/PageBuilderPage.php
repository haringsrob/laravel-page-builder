<?php

namespace Haringsrob\LaravelPageBuilder\Models;

use ErrorException;
use Haringsrob\LaravelPageBuilder\Exceptions\ComponentClassMissingException;
use Haringsrob\LaravelPageBuilder\PageBuilderCollection;
use Haringsrob\LaravelPageBuilder\View\BuilderComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageBuilderPage extends Model
{
    use HasFactory;

    protected $casts = [
        'structure' => 'json'
    ];

    public function getStructureForData(array $data): BuilderComponent
    {
        if (!class_exists($data['class'])) {
            throw new ComponentClassMissingException();
        }
        $arguments = [];

        // Get the original fields, so we can handle them in the correct order.
        $fields = $data['class']::getForm();

        foreach ($fields as $field => $type) {
            switch ($type) {
                case BuilderComponent::TYPE_TEXT:
                case BuilderComponent::TYPE_WYSIWYG:
                    $arguments[] = $data['fields'][$field]['content'];
                    break;
                case BuilderComponent::TYPE_CHILD:
                    $arguments[] = $this->getStructureForData($data['fields'][$field]['content']);
                    break;
                case BuilderComponent::TYPE_CHILDREN:
                    $sublist = [];
                    foreach ($data['fields'][$field]['content'] as $childItem) {
                        $sublist[] = $this->getStructureForData($childItem);
                    }
                    $arguments[] = $sublist;
                    break;
            }
        }

        return new $data['class'](...$arguments);
    }

    public function toStructureCollection(): PageBuilderCollection
    {
        $renderStructure = new PageBuilderCollection();

        // Quit early if the page is blank.
        if (count($this->structure['data']) == 0) {
            return $renderStructure;
        }

        try {
            foreach ($this->structure['data'] as $mainComponent) {
                $renderStructure->push($this->getStructureForData($mainComponent));
            }
        } catch (ComponentClassMissingException $e) {

            // At this point we have an error and we know we cannot render.
            // @todo: Build in some recovery?
            $renderStructure = new PageBuilderCollection();
        }

        return $renderStructure;
    }
}
