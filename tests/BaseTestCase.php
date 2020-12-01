<?php

namespace Haringsrob\LaravelPageBuilder\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\View;

abstract class BaseTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        View::addLocation(__DIR__ . '/fixtures/Components/');
    }
}
