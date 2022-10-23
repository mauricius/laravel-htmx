<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithContainer;
use Illuminate\Support\Facades\View;
use Mauricius\LaravelHtmx\LaravelHtmxServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use InteractsWithContainer;

    public function setUp(): void
    {
        parent::setUp();

        View::addLocation(__DIR__.'/views');
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelHtmxServiceProvider::class,
        ];
    }
}
