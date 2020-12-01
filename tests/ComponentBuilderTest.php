<?php

namespace Haringsrob\LaravelPageBuilder\Tests;

// @todo: Cant this autoload?
include_once __DIR__ . '/BaseTestCase.php';

use Haringsrob\LaravelPageBuilder\LaravelPageBuilderServiceProvider;
use Haringsrob\LaravelPageBuilder\Tests\BaseTestCase;
use Haringsrob\LaravelPageBuilder\PageBuilderCollection;
use Haringsrob\LaravelPageBuilder\Tests\Fixtures\Components\DivWithChildTestComponent;
use Haringsrob\LaravelPageBuilder\Tests\Fixtures\Components\DivWithManyChildTestComponent;
use Haringsrob\LaravelPageBuilder\Tests\Fixtures\Components\HeadingTestComponent;
use Haringsrob\LaravelPageBuilder\View\Components\BuilderContent;
use Illuminate\Contracts\View\View;

class ComponentBuilderTest extends BaseTestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelPageBuilderServiceProvider::class];
    }

    public function testEmptyBuilderContent()
    {
        $collection = new PageBuilderCollection();

        $component = new BuilderContent($collection);

        $html = $component->render()->render();

        $this->assertEmpty($html);
    }

    public function testSimpleHeading()
    {
        // @todo: Cant this autoload?
        require_once __DIR__ . '/fixtures/Components/HeadingTestComponent.php';
        $collection = new PageBuilderCollection();
        $collection->push(new HeadingTestComponent('Heading text'));

        $component = new BuilderContent($collection);

        $html = $component->render();
        $expectedHtml = '<h1>Heading text</h1>';

        $this->assertEquals($expectedHtml, $this->withoutNewLines($html));
    }

    public function testNestedComponents()
    {
        // @todo: Cant this autoload?
        require_once __DIR__ . '/fixtures/Components/HeadingTestComponent.php';
        require_once __DIR__ . '/fixtures/Components/DivWithChildTestComponent.php';
        $collection = new PageBuilderCollection();
        $collection->push(
            new DivWithChildTestComponent(
                new HeadingTestComponent('Heading text')
            )
        );

        $component = new BuilderContent($collection);

        $html = $component->render();
        $expectedHtml = '<div class="divwithchild"><h1>Heading text</h1></div>';

        $this->assertEquals($expectedHtml, $this->withoutNewLines($html));
    }

    public function testDoubleNestedComponents()
    {
        // @todo: Cant this autoload?
        require_once __DIR__ . '/fixtures/Components/HeadingTestComponent.php';
        require_once __DIR__ . '/fixtures/Components/DivWithChildTestComponent.php';
        $collection = new PageBuilderCollection();
        $collection->push(
            new DivWithChildTestComponent(
                new DivWithChildTestComponent(
                    new HeadingTestComponent('Heading text')
                )
            )
        );

        $component = new BuilderContent($collection);

        $html = $component->render();
        $expectedHtml = '<div class="divwithchild"><div class="divwithchild"><h1>Heading text</h1></div></div>';

        $this->assertEquals($expectedHtml, $this->withoutNewLines($html));
    }

    public function testNestedWithManyComponents()
    {
        // @todo: Cant this autoload?
        require_once __DIR__ . '/fixtures/Components/HeadingTestComponent.php';
        require_once __DIR__ . '/fixtures/Components/DivWithChildTestComponent.php';
        require_once __DIR__ . '/fixtures/Components/DivWithManyChildTestComponent.php';
        $collection = new PageBuilderCollection();
        $collection->push(
            new DivWithManyChildTestComponent(
                [
                    new HeadingTestComponent('Heading text'),
                    new HeadingTestComponent('Second Heading text')
                ]
            )
        );

        $component = new BuilderContent($collection);

        $html = $component->render();
        $expectedHtml = '<div class="divwithchild"><h1>Heading text</h1><h1>Second Heading text</h1></div>';

        $this->assertEquals($expectedHtml, $this->withoutNewLines($html));
    }

    private function withoutNewLines(View $html): string
    {
        return trim(preg_replace('/\n/', '', $html->render()));
    }
}
